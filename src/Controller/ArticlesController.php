<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Mailer\Email;

use App\Model\Entity\Commentaire;

class ArticlesController extends AppController
{
    public $paginate = [
        'Articles' => [
            'fields' => ['Articles.id'],
            'limit' => 6,
            'order' => ['Articles.dateCreation' => 'desc']
        ],
        'Commentaires' => [
            'fields' => ['Commentaires.id'],
            'limit' => 10,
            'order' => ['Commentaires.dateCreation' => 'desc']
        ]
    ];

    public function showList($conditions = '')
    {
        //récupération des conditions pour la requête article
        $rubrique_id = null;
        $tabConditions = array();
        if (!empty($conditions)) {
            $tabConditions = unserialize($conditions);
        } elseif (!empty($_GET)) {
            if (isset($_GET['conditions'])) {
                $tabConditions = unserialize($_GET['conditions']);
            } else {
                $tabConditions = $_GET;
            }
        } elseif (!empty($_POST)) {
            $tabConditions = $_POST;
        }

        if (isset($tabConditions['rubrique_id']) && !empty($tabConditions['rubrique_id'])) {
            $rubrique_id = $tabConditions['rubrique_id'];
        }

        //articles
        $tabParamsSQL = array('contain' => ['Rubriques']);
        $tabConditionsSQL = array();
        if (isset($tabConditions['keyword'])) {
            $tabConditionsSQL[] = "(titre LIKE '%" . $tabConditions['keyword'] . "%'"
            . " OR Articles.descriptif LIKE '%" . $tabConditions['keyword'] . "%'"
            . " OR contenu LIKE '%" . $tabConditions['keyword'] . "%')";
        }
        if (!empty($rubrique_id)) {
            $tabConditionsSQL[] = 'rubrique_id =' . $rubrique_id;
        }
        $tabParamsSQL['conditions'] = $tabConditionsSQL;
        $query_article = $this->Articles->find('all', $tabParamsSQL);
        $resultat = $query_article->toArray();//exécute la requête
        $nbResultat = count($resultat);
        $articles = $this->paginate($query_article);

        //rubriques
        $table_rubrique = TableRegistry::getTableLocator()->get('Rubriques');
        $query_rubrique = $table_rubrique->find('all');
        $rubriques = $query_rubrique->toArray();//exécute la requête
        $listeRubriques = array();
        foreach ($rubriques as $rubrique) {
            $listeRubriques[$rubrique->id] = $rubrique->nom;
        }

        $this->set([
            'articles' => $articles,
            'nbResultat' => $nbResultat,
            'listeRubriques' => $listeRubriques,
            'rubrique_id' => $rubrique_id,
            'tabConditions' => $tabConditions
        ]);

        $this->render('/Articles/list');
    }

    public function showView($id)
    {
        $article = $this->Articles->get($id);

        $hash = '';
        $tabObjetsCom = array();
        $tabObjetsCom[$id] = new Commentaire();//objet commentaire du form. com. de l'article

        $dataFormCom = $this->request->getData();
        //soumission du formulaire commentaire
        if (!empty($dataFormCom)) {
            $nomObjetCom = (isset($dataFormCom['commentaire_id'])) ? $id . '_' . $dataFormCom['commentaire_id'] : $id;
            $tabObjetsCom[$nomObjetCom] = $this->createCommentaire($dataFormCom);

            //nouvel objet + remet le formulaire à vide
            if (!$tabObjetsCom[$nomObjetCom]->getErrors()) {
                $hash = $tabObjetsCom[$nomObjetCom]->article_id . '_' . $tabObjetsCom[$nomObjetCom]->id;
                $tabObjetsCom[$nomObjetCom] = new Commentaire();
                $this->request->data = null;
            }
        }

        //article précédent
        $query_articlePrecedent = $this->Articles
        ->find()
        ->select('id')
        ->where(['id <' => $id])
        ->order('id DESC')
        ->limit(1);
        $articlePrecedent = $query_articlePrecedent->first();
        $idArticlePrecedent = (!empty($articlePrecedent)) ? $articlePrecedent->id : null;

        //article suivant
        $query_articleSuivant = $this->Articles
        ->find()
        ->select('id')
        ->where(['id >' => $id])
        ->order('id ASC')
        ->limit(1);
        $articleSuivant = $query_articleSuivant->first();
        $idArticleSuivant = (!empty($articleSuivant)) ? $articleSuivant->id : null;

        //commentaires
        $table_commentaire = TableRegistry::getTableLocator()->get('Commentaires');
        $query_commentaires = $table_commentaire
        ->find()
        ->where("Commentaires.article_id = $id")
        ->andWhere('Commentaires.commentaire_id IS NULL')
        ->contain('Commentaires2', function ($c2) {
            return $c2->order('dateCreation ASC')
            ->contain('Users');
        })
        ->contain(['Users'])
        ->order('Commentaires.dateCreation ASC');
        $query_commentaires->toArray();//exécute la requête
        $commentaires = $this->paginate($query_commentaires);

        //crée un objet com. pour chq formulaire de chq com. existant
        foreach ($commentaires as $com) {
            $nomObjetCom = $com->article_id . '_' . $com->id;
            if (!isset($tabObjetsCom[$nomObjetCom])) {
                $tabObjetsCom[$nomObjetCom] = new Commentaire();
            }
        }

        $this->set([
            'article' => $article,
            'idArticlePrecedent' => $idArticlePrecedent,
            'idArticleSuivant' => $idArticleSuivant,
            'commentaires' => $commentaires,
            'tabObjetsCom' => $tabObjetsCom,
            'hash' => $hash
        ]);

        $this->render('view');
    }

    public function createCommentaire($dataForm) {
        $idUser = null;

        /* user */
        if (!empty($dataForm['email'])) {
            $table_user = TableRegistry::getTableLocator()->get('Users');
            //recherche de l'utilisateur en bdd
            $query_user = $table_user->find('all', [
                'conditions' => ['email =' => $dataForm['email']],
                'limit' => 1
            ]);
            $user = $query_user->first();
            //s'il n'existe pas on le crée
            if (empty($user)) {
                $user = $table_user->newEntity();
                $user->set('role', 'visiteur');
                $user->set('username', $dataForm['username']);
                $user->set('email', $dataForm['email']);
                $table_user->save($user);
            }

            $idUser = $user->id;
        }

        //création du commentaire
        $table_com = TableRegistry::getTableLocator()->get('Commentaires');
        $dataForm['user_id'] = $idUser;
        $dataForm['dateCreation'] = Time::now();
        $commentaire = $table_com->newEntity($dataForm);//affectation des valeurs + check form

        if (!$commentaire->getErrors()) {
            //sauvegarde
            $table_com->save($commentaire);

            //envoie un email à l'auteur du 1er com. et les autres utilisateurs concernés
            if (!empty($commentaire->commentaire_id) && !empty($user->email)) {
                $comId = $commentaire->commentaire_id;
                $articleId = $dataForm['article_id'];

                $users = $table_user
                ->find()
                ->where(['Users.id !=' => $idUser])//exclue l'utilisateur qui a crée le com.
                //innerJoinWith : permet de rajouter des conditions dans le join sans charger les champs
                //rque : les champs se chargent quand même ici je ne sais pas pourquoi
                ->innerJoinWith('Commentaires', function ($c) use($comId, $articleId) {
                    return $c
                    ->where([
                        'OR' => [
                            ['Commentaires.id' => $comId],
                            ['Commentaires.commentaire_id' => $comId]
                        ]
                    ])
                    ->contain(['Articles' => ['conditions' => ['Articles.id = ' . $articleId]]]);
                })
//                ->contain('Commentaires') //charge les champs de la table (pas besoin de contain apparement dans ce cas-ci)
                ->toArray();

                //s'il y a au moins un utilisateur à qui il faut envoyer un email
                if (!empty($users)) {
                    $article = $users[0]->commentaires[0]->article;

                    $tabIdUsers = array();
                    foreach ($users as $user2) {
                        //si l'utilisateur n'est pas désinscrit du blog
                        //et qu'il n'a pas déjà reçu le mail
                        if (!empty($user2->email) && !in_array($user2->id, $tabIdUsers)) {
                            $tabIdUsers[] = $user2->id;
                            $this->sendEmailReponse($article, $user2);
                        }
                    }
                }
            }
        }

        return $commentaire;
    }

    public function sendEmailReponse($article, $user) {
        $configEmail = Email::config('default');

        $email = new Email();
        $email->setEmailFormat('html');
        $email->setHeaders([
            'From' => 'CakePHP Training : ' . $configEmail['from'],
            'To' => $user->email,
            'X-Mailer' => 'PHP/' . phpversion(),
            'MIME-Version' => 1.0,
            'Content-Type' => 'text/html; charset=UTF-8'
        ]);
        $email->setTo($user->email);
        $email->setSubject("CakePHP Training - Réponse à votre commentaire");
        $email->viewVars(['user' => $user, 'article' => $article]);
        $email->viewBuilder()->setLayout('default');
        $email->viewBuilder()->setTemplate('nouveau_commentaire');
        $email->setAttachments([WWW_ROOT . 'img/cake.png']);
        $email->send();
    }

    public function sendEmailTest() {
        $this->autoRender = false;//pas de render
        $configEmail = Email::config('default');

        $email = new Email();
        $email->setEmailFormat('html');
        $email->setHeaders([
            'From' => 'CakePHP Training : ' . $configEmail['from'],
            'To' => $configEmail['from'],
//            'To' => 'test-1ilze38d7@srv1.mail-tester.com',
            'X-Mailer' => 'PHP/' . phpversion(),
            'MIME-Version' => 1.0,
            'Content-Type' => 'text/html; charset=UTF-8'
        ]);
        $email->setTo($configEmail['from']);
//        $email->setTo('test-1ilze38d7@srv1.mail-tester.com');
        $email->setSubject("CakePHP Training - Réponse à votre commentaire");
        $email->viewBuilder()->setLayout('default');
        $email->viewBuilder()->setTemplate('test');
        $email->setAttachments([WWW_ROOT . 'img/cake.png']);
        $email->send();

        echo 'OK';
    }
}
