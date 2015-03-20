<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dungeon Crawler Mini-RPG</title>
    <?= link_tag("css/bootstrap.min.css"); ?>
    <?= link_tag("css/bootstrap-theme.min.css"); ?>
    <?= link_tag("css/rpg.css"); ?>
    <?= link_tag("css/battle.css"); ?>
    <?= link_tag("css/misc.css"); ?>
</head>
<body>
    <div class="center-outer"><div class="center-middle">
        <div class="center-content center-text field">
            <div class="two-column pull-left enemies">
                <div id="enemy-field-0" class="field-sprite flip"></div>
                <div id="enemy-field-1" class="field-sprite flip"></div>
                <div id="enemy-field-2" class="field-sprite flip"></div>
                <div id="enemy-field-3" class="field-sprite flip"></div>
            </div>
            <div class="two-column pull-left player">
                <div id="player-field-0" class="field-sprite"></div>
                <div id="player-field-1" class="field-sprite"></div>
                <div id="player-field-2" class="field-sprite"></div>
                <div id="player-field-3" class="field-sprite"></div>
            </div>
        </div>
        <div class="center-content battle-ui">
            <div class="ui-left pull-left">
                <button id="fight" class="btn btn-primary">Fight</button>
                <button id="run" class="btn btn-primary">Run Away</button>
            </div>
            <div class="ui-right pull-left">
                <div id="party-0" class="media party-slot">
                    <div class="media-left media-middle">
                        <div class="media-object avatar"></div>
                    </div>
                    <div class="media-body status">
                        <div class="name"></div>
                        <div class="health"></div>
                        <div class="ct progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <div id="party-1" class="media party-slot">
                    <div class="media-left media-middle">
                        <div class="media-object avatar"></div>
                    </div>
                    <div class="media-body status">
                        <div class="name"></div>
                        <div class="health"></div>
                        <div class="ct progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <div id="party-2" class="media party-slot">
                    <div class="media-left media-middle">
                        <div class="media-object avatar"></div>
                    </div>
                    <div class="media-body status">
                        <div class="name"></div>
                        <div class="health"></div>
                        <div class="ct progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <div id="party-3" class="media party-slot">
                    <div class="media-left media-middle">
                        <div class="media-object avatar"></div>
                    </div>
                    <div class="media-body status">
                        <div class="name"></div>
                        <div class="health"></div>
                        <div class="ct progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div></div>
    <div class="modal fade" id="modal" tabIndex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>
    </div></div></div>
</body>
<script type="text/javascript" src="<?= base_url('js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/string.extended.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/base.url.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/entities.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/battle.js'); ?>"></script>
<script type="text/javascript">
var battle, uiupdate,
    stepupdate, endupdate;
$(document).ready(function () {
    initBaseURL("");

    battle = new Battle();
    battle.init(<?= $this->session->userdata("id"); ?>);
    battle.setUI({
        "fp": "player-field-{0}",
        "sp": "party-{0}",
        "fe": "enemy-field-{0}",
        "mo": "modal"
    });

    $("#run").click(function() {
        battle.issue("retreat");
    });
    $("#fight").click(function() {
        battle.issue("attack");
    });
    $("#modal").on("hidden.bs.modal", function(e) {
        $(location).attr("href", "town");
    });

    uiupdate = setInterval(battle.updateUI, 100);
    stepupdate = setInterval(battle.step, 1000);
    endupdate = setInterval(endBattle, 1500);
});
function endBattle() {
    if(battle.hasEnded()) {
        battle.postmortem();
        battle.saveChanges();
        clearInterval(uiupdate);
        clearInterval(stepupdate);
        clearInterval(endupdate);
    }
}
</script>
</html>
