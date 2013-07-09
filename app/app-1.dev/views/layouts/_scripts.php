<?php
/**
 * @var $this WebController
 */

// fix bootsrtap padding on top with responsive views
if (!isset($this->showNavBar) || !$this->showNavBar) {
    cs()->registerCSS('reset', 'body{padding-top:20px;}', '', array('order' => -6));
}
cs()->registerCSSFile(au() . '/css/style.css', '', array('order' => -6));

// font awesome
$this->widget('FontAwesome');

// load here so modals don't have to load it
cs()->registerCoreScript('yiiactiveform');

// modal for popups
$this->widget('Modal');

// dropdown JS doesn't work on iPad
// https://github.com/twitter/bootstrap/issues/2975#issuecomment-6659992
cs()->registerScript('bootstrap-dropdown-fix', "$('body').on('touchstart.dropdown', '.dropdown-menu', function (e) { e.stopPropagation(); });", CClientScript::POS_END);

// qtip for tooltips
$this->widget('QTip');
/*
// google analytics
$this->beginWidget('JavaScriptWidget', array('position' => CClientScript::POS_END));
?>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-35590187-1']);
    _gaq.push(['_setDomainName', 'keyterminal.com']);
    _gaq.push(['_setAllowLinker', true]);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();

</script><?php
$this->endWidget();

// uservoice
cs()->registerCss('uservoice', '#uvTab{ display:none !important; }');
$this->beginWidget('JavaScriptWidget', array('position' => CClientScript::POS_END));
?>
<script type="text/javascript">
    function showUserVoicePopupWidget() {
        var uvOptions = {};
        var uv = document.createElement('script');
        uv.type = 'text/javascript';
        uv.async = true;
        uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/nZDkIYP5Ba7718bx19ZBbQ.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(uv, s);
        setTimeout(executeUserVoicePopupWidget, 100);
    }
    function executeUserVoicePopupWidget() {
        if (UserVoice) {
            UserVoice.showPopupWidget();
        }
        else {
            setTimeout(executeUserVoicePopupWidget, 100);
        }
    }
</script><?php
$this->endWidget();
*/

// theme scripts
$this->renderPartial('/layouts/_theme_scripts');