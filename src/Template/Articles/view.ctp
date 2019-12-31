<nav aria-label="breadcrumb" class="row d-flex">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><?= $this->Html->link('Articles', '/articles/index') ?></li>
    <li class="breadcrumb-item"><?= $this->Html->link('Article', '/articles/view') ?></li>
    <li class="breadcrumb-item active" aria-current="page">Exemple</li>
  </ol>
</nav>

<h1 class="text-center mb-5">Veneris</h1>

<div class="mb-5">
    <p>
    Quibus ita sceleste patratis Paulus cruore perfusus reversusque ad principis castra multos
    coopertos paene catenis adduxit in squalorem deiectos atque maestitiam, quorum adventu
    intendebantur eculei uncosque parabat carnifex et tormenta. et ex is proscripti sunt plures
    actique in exilium alii, non nullos gladii consumpsere poenales. nec enim quisquam facile meminit
    sub Constantio, ubi susurro tenus haec movebantur, quemquam absolutum.
    </p>

    <p>
    Cyprum itidem insulam procul a continenti discretam et portuosam inter municipia crebra urbes duae
    faciunt claram Salamis et Paphus, altera Iovis delubris altera Veneris templo insignis. tanta
    autem tamque multiplici fertilitate abundat rerum omnium eadem Cyprus ut nullius externi indigens
    adminiculi indigenis viribus a fundamento ipso carinae ad supremos usque carbasos aedificet
    onerariam navem omnibusque armamentis instructam mari committat.
    </p>

    <p>
    Denique Antiochensis ordinis vertices sub uno elogio iussit occidi ideo efferatus, quod ei
    celebrari vilitatem intempestivam urgenti, cum inpenderet inopia, gravius rationabili responderunt;
    et perissent ad unum ni comes orientis tunc Honoratus fixa constantia restitisset.
    </p>

    <p>
    Itaque verae amicitiae difficillime reperiuntur in iis qui in honoribus reque publica versantur;
    ubi enim istum invenias qui honorem amici anteponat suo? Quid? Haec ut omittam, quam graves, quam
    difficiles plerisque videntur calamitatum societates! Ad quas non est facile inventu qui descendant.
    Quamquam Ennius recte.
    </p>

    <p>
    Nihil est enim virtute amabilius, nihil quod magis adliciat ad diligendum, quippe cum propter
    virtutem et probitatem etiam eos, quos numquam vidimus, quodam modo diligamus. Quis est qui C.
    Fabrici, M'. Curi non cum caritate aliqua benevola memoriam usurpet, quos numquam viderit? quis
    autem est, qui Tarquinium Superbum, qui Sp. Cassium, Sp. Maelium non oderit? Cum duobus ducibus de
    imperio in Italia est decertatum, Pyrrho et Hannibale; ab altero propter probitatem eius non nimis alienos animos habemus, alterum propter crudelitatem semper haec civitas oderit.
    </p>

    <p>
    Proinde concepta rabie saeviore, quam desperatio incendebat et fames, amplificatis viribus ardore
    incohibili in excidium urbium matris Seleuciae efferebantur, quam comes tuebatur Castricius
    tresque legiones bellicis sudoribus induratae.
    </p>

    <p>
    Iam virtutem ex consuetudine vitae sermonisque nostri interpretemur nec eam, ut quidam docti,
    verborum magnificentia metiamur virosque bonos eos, qui habentur, numeremus, Paulos, Catones,
    Galos, Scipiones, Philos; his communis vita contenta est; eos autem omittamus, qui omnino nusquam
    reperiuntur.
    </p>

    <p>
    Principium autem unde latius se funditabat, emersit ex negotio tali. Chilo ex vicario et coniux
    eius Maxima nomine, questi apud Olybrium ea tempestate urbi praefectum, vitamque suam venenis
    petitam adseverantes inpetrarunt ut hi, quos suspectati sunt, ilico rapti conpingerentur in
    vincula, organarius Sericus et Asbolius palaestrita et aruspex Campensis.
    </p>

    <p>
    Sed maximum est in amicitia parem esse inferiori. Saepe enim excellentiae quaedam sunt, qualis
    erat Scipionis in nostro, ut ita dicam, grege. Numquam se ille Philo, numquam Rupilio, numquam
    Mummio anteposuit, numquam inferioris ordinis amicis, Q. vero Maximum fratrem, egregium virum
    omnino, sibi nequaquam parem, quod is anteibat aetate, tamquam superiorem colebat suosque omnes
    per se posse esse ampliores volebat.
    </p>

    <p>
    Thalassius vero ea tempestate praefectus praetorio praesens ipse quoque adrogantis ingenii,
    considerans incitationem eius ad multorum augeri discrimina, non maturitate vel consiliis
    mitigabat, ut aliquotiens celsae potestates iras principum molliverunt, sed adversando
    iurgandoque cum parum congrueret, eum ad rabiem potius evibrabat, Augustum actus eius
    exaggerando creberrime docens, idque, incertum qua mente, ne lateret adfectans. quibus mox Caesar
    acrius efferatus, velut contumaciae quoddam vexillum altius erigens, sine respectu salutis
    alienae vel suae ad vertenda opposita instar rapidi fluminis irrevocabili impetu ferebatur.
    </p>
</div>

<form class="mb-5">
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Commentaires</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
  </div>
</form>

<div class="btnArticle">
    <button type="button" class="btn btn-dark"><?= $this->Html->link('Retour aux articles', '/articles/index') ?></button>
    <button type="button" class="btn btn-dark"><?= $this->Html->link('Article précédent', '/articles/view') ?></button>
    <button type="button" class="btn btn-dark"><?= $this->Html->link('Article suivant', '/articles/view') ?></button>
</div>