<?php
$token = token('inscription');
?>
<div id="callaction">
    <div id="signup">
        <form name="form" id="form" class="form" action="index.php?page=validation-inscription" onsubmit="return validate(this)" method="post">
            <fieldset>
                <legend>Inscrivez-vous gratuitement</legend>
                <div class="fm-req">
                    <label for="email">Email : </label>
                    <input name="email" id="email" type="text" /><div class="error-input">Erreur</div><br/>
                </div>
                <div class="fm-req">
                    <label for="password2">Mot de passe : </label>
                    <input name="password2" id="password2" type="password" onkeyup="passwordStrength(this.value)" /><div class="error-input">Erreur</div><br/>
                </div><div class="fm-opt">
                    <div id="passwordDescription">Mot de passe vide</div>
                    <div id="passwordStrength" class="strength0"></div>
                </div>
                <div class="fm-req">
                    <label for="confirmation">Confirmation : </label>
                    <input name="confirmation" id="confirmation" type="password" /><div class="error-input">Erreur</div><br/>
                </div>
                <div class="fm-req">
                    <label for="pseudo2">Pseudo : </label>
                    <input name="pseudo2" id="pseudo2" type="text" /><div class="error-input">Erreur</div><br/>
                    <div class="fm-opt"></div>
                </div>

                <?php

                function select_list($name, $from, $to) {
                    for ($x = $from; $x <= $to; $x++) {
                        echo'<option value="' . $x . '">' . $x . '</option>';
                    }
                }
                ?>

                <div class="fm-req">
                    <label for="pays">Pays : </label>
                    <select id="pays" name="pays">
                        <option value="France" selected="selected">France</option>
                        <option value="Bel">Belgique</option>
                    </select>
                    <div class="error-input">Erreur</div><br/>
                </div>
                <div class="fm-req hideform">
                    <label for="naissance">Date de naissance : </label>
                    <select name="day" id="day">
                        <option value="none" selected="selected">Jour</option>
                        <?php select_list("Jour", 1, 31); ?>
                    </select>
                    <select id="month" name="month">
                        <option value="none" selected="selected">Mois </option>
                        <?php select_list("Mois", 1, 12); ?>
                    </select>
                    <select id="year" name="year">
                        <option value="none" selected="selected">Année</option>
                        <?php select_list("Année", 1910, date('Y')); ?>
                    </select>
                    <div class="error-input">Erreur</div><br/>
                </div>

                <div class="fm-req hideform">
                    <label for="cp">Code Postal : </label>
                    <input type='text' name='cp' id='cp' autocomplete="off" maxlength="5">
                    <div class="error-input">Erreur</div>
                </div>
                <div class="fm-req hideform">
                    <label for="ville">Votre ville :</label>
                    <select name="ville" id="ville">
                    </select>
                    <div class="error-input">Erreur</div><br id="ville"/>
                </div>
                <div class="fm-req hideform">
                    <label for="sex">Vous êtes : </label>
                    <input name="sex" type="radio" id="etes-yes" value="yes" checked="checked" />Homme
                    <input name="sex" type="radio" id="etes-no" value="no" />Femme
                    <div class="error-input">Erreur</div><br/>
                </div>
                <input type="hidden" id="captcha" name="captcha" />
                <input type="hidden" id="token" name="token" value="<?php echo $token ?>" />
                <div class="fm-req hideform">
                    <label for='conditions' id="condi">J'accepte les conditions d'inscription</label><input type='checkbox' name='conditions' id='conditions' />
                    <div class="error-input">Erreur</div><br/><br/>
                    <hr class="clear"/>
                </div>
            </fieldset>
            <div id="fm-submit" class="fm-req">
                <input name="submit" value="Inscrivez-vous" type="submit" id="submit" />
            </div>
        </form>
    </div>
</div>

<?php
$first = rand(1, 7);
$second = rand(1, 7);

if ($first == $second) {
    while ($first == $second) {
        $second = rand(1, 7);
    }
}

$fichier = "http://www.meetoparty.power-heberg.com/XML/homepage-quote.xml";

if ($flux = simplexml_load_file($fichier)) {

    // print_r($flux);

    foreach ($flux as $valeur) {

        if ($valeur->id == $first) {
            $result2['picture'] = utf8_decode($valeur->picture);
            $result2['pre-text'] = utf8_decode($valeur->pretext);
            $result2['text'] = utf8_decode($valeur->text);
        }

        if ($valeur->id == $second) {
            $result3['picture'] = utf8_decode($valeur->picture);
            $result3['pre-text'] = utf8_decode($valeur->pretext);
            $result3['text'] = utf8_decode($valeur->text);
        }
    }
}
?>
<script type="text/javascript">
    first = <?php echo $first ?>;
    second = <?php echo $second ?>;

    function test(){
        $("#pretext1").css({'filter' : 'alpha(opacity=80)'}).fadeOut("slow");
        $("#pretext2").css({'filter' : 'alpha(opacity=80)'}).fadeOut("slow");
        $("#text1").css({'filter' : 'alpha(opacity=80)'}).fadeOut("slow");
        $("#text2").css({'filter' : 'alpha(opacity=80)'}).fadeOut("slow");
        $('#quote1').css({'filter' : 'alpha(opacity=80)'}).fadeOut("slow");
        $('#quote2').css({'filter' : 'alpha(opacity=80)'}).fadeOut("slow");
        $('#picture1').css({'filter' : 'alpha(opacity=80)'}).fadeOut("slow");
        $('#picture2').fadeOut("slow",function(){
            $.ajax({
                type: 'GET',
                url: 'js/Ajax-PHP/inscription/homepage-quote.php',
                data: "first="+first+"&second="+second,
                dataType: 'json',
                async: false,
                success:
                    function(json) {
                    var picture1 = json.picture1;
                    var picture2 = json.picture2;
                    var pretext1 = json.pretext1;
                    var pretext2 = json.pretext2;
                    var text1 = json.text1;
                    var text2 = json.text2;
											
                    $("#pretext1").css({'filter' : 'alpha(opacity=80)'}).fadeIn("slow").text(pretext1);
                    $("#pretext2").css({'filter' : 'alpha(opacity=80)'}).fadeIn("slow").text(pretext2);
                    $("#text1").css({'filter' : 'alpha(opacity=80)'}).fadeIn("slow").text(text1);
                    $("#text2").css({'filter' : 'alpha(opacity=80)'}).fadeIn("slow").text(text2);
                    $('#picture1').attr('src', '../Images/homepage-quote/'+picture1).css({'filter' : 'alpha(opacity=80)'}).fadeIn("slow");
                    $('#picture2').attr('src', '../Images/homepage-quote/'+picture2).css({'filter' : 'alpha(opacity=80)'}).fadeIn("slow");
                    $('#quote1').css({'filter' : 'alpha(opacity=80)'}).fadeIn("slow");
                    $('#quote2').css({'filter' : 'alpha(opacity=80)'}).fadeIn("slow");
                }
            });
        });}

    setInterval( "test()", 7000 );

</script>


<div id="examples">
    <div class="pic">
        <div class="ex-box" id="quote1"><img id="picture1" src="../Images/homepage-quote/<?php echo $result2['picture'] ?>" /></div>
        <p><span><div id="pretext1" style="font-weight:normal; font-style:italic; font-size:18px; color:#5b5b5b;"><?php echo $result2['pre-text'] ?></div></span><div id="text1" style="font-weight:bold; font-size:18px; color:#5b5b5b;"><?php echo $result2['text'] ?></div></p>
    </div>
    <div class="pic">
        <div class="ex-box" id="quote2"><img id="picture2" src="../Images/homepage-quote/<?php echo $result3['picture'] ?>" /></div>
        <p><span><div id="pretext2" style="font-weight:normal; font-style:italic; font-size:18px; color:#5b5b5b;"><?php echo $result3['pre-text'] ?></div></span><div id="text2" style="font-weight:bold; font-size:18px; color:#5b5b5b;"><?php echo $result3['text'] ?></div></p>
    </div>
    <div class="clear"></div>
</div>
<div id="hp_bg">
    <div class="hp_txt">
        <legend><span class="hometitle">Qu'est ce que Meetoparty ?</span></legend>
        <p>Origine de vos futurs évènements, ce site est le cocktail parfait entre réseau social et site de rencontres. Cette révolution dont vous connaissez déjà le principe, s’offre à vous comme étant un outil gratuit et simple d’utilisation.
        Meetoparty est le résultat final d’une étude sur l’évolution des relations sociales. Aujourd’hui, peu sont ceux qui osent aller vers une personne inconnue. Meetoparty a pour objectif de changer le court des choses.</p>
        <p>Meetoparty vous propose un moyen ludique et attractif d’organiser des rencontres réelles avec des inconnus dans le cadre du partage de vos passions. A la clé ? Se créer de nouveaux liens d’amitié et plus si affinités.</p>
    </div>
    <div class="hp_txt">
        <legend><span class="hometitle">Faites des rencontres qui vous ressemblent !</span></legend>
        <p>Marre des soirées ou sorties ou vous ne vous sentez pas à votre place ? Envie de nouvelles expériences ou de faire de nouvelles connaissance ? Meetoparty est fait pour vous !</p>
        <p>Meetoparty vous accompagne et vous aide dans votre démarche en vous permettant de créer des centaines d'événements, sorties ou soirées possibles en lien avec vos passions !</p>
        <p>Déclencheur de rencontres, nous vous souhaitons de merveilleux moments.</p>
    </div>
    <div class="clear"></div>
</div>
<div id="conditions-popup" class="hp_txt">
    <p>Ceci est le texte ou l'on mettra les conditions d'utilisation ou il lien menant à la page !</p>
    <p>Si vous refusez les conditions d'inscription, vous serez redirigez vers l'accueil.</p>
</div>	