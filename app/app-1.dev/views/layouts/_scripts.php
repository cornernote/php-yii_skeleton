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

// Support for AJAX loaded modal window.
$this->beginWidget('JavaScriptWidget', array('position' => CClientScript::POS_END));
?>
<script type="text/javascript">
    $('[data-toggle="modal-remote"]').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var modalRemote = $('#modal-remote');

        $.ajax({
            url:url,
            beforeSend:function (data) {
                if (!modalRemote.length) modalRemote = $('<div class="modal hide fade" id="modal-remote"></div>');
                modalRemote.html('<div class="modal-header"><h3><?php echo t('Loading...'); ?></h3></div><div class="modal-body"><div class="modal-remote-indicator"></div>');
                modalRemote.modalResponsiveFix();
                modalRemote.touchScroll();
                modalRemote.modal();
            },
            success:function (data) {
                modalRemote.html(data);
                $(window).resize();
                //modalRemote.children('input:text:visible:first').focus();
                $('#modal-remote input:text:visible:first').focus();
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                modalRemote.children('.modal-header').html('<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button><h3><?php echo t('Error!'); ?></h3>');
                modalRemote.children('.modal-body').html(XMLHttpRequest.responseText);
            }
        });
    });
</script><?php
$this->endWidget();

// fix modals on mobile devices
// http://niftylettuce.github.com/twitter-bootstrap-jquery-plugins/
cs()->registerScriptFile(au() . '/modal-responsive-fix/touchscroll.js', CClientScript::POS_HEAD, array('order' => 1));
cs()->registerScriptFile(au() . '/modal-responsive-fix/modal-responsive-fix.min.js', CClientScript::POS_HEAD, array('order' => 1));
$this->beginWidget('JavaScriptWidget', array('position' => CClientScript::POS_END));
?>
<script type="text/javascript">
    $('.modal').modalResponsiveFix();
    $('.modal').touchScroll();
</script><?php
$this->endWidget();
cs()->registerCSS('modal-responsive-fix', '.modal-body { -webkit-overflow-scrolling:touch; } body.modal-open{overflow: hidden;} @media (max-width: 767px) {.modal.fade.in {top: 10px !important;}}', '', array('order' => 10));

// dropdown JS doesn't work on iPad
// https://github.com/twitter/bootstrap/issues/2975#issuecomment-6659992
cs()->registerScript('bootstrap-dropdown-fix', "$('body').on('touchstart.dropdown', '.dropdown-menu', function (e) { e.stopPropagation(); });", CClientScript::POS_END);

// auto-qtip on <a title="something">link</a>
cs()->registerScript('qtip2', '
$(document).on("mouseover", "a[title],i[title],.icon[title]", function(event) {
    $(this).qtip({
        overwrite: false,
        style: {
            classes: "qtip-bootstrap"
        },
        show: {
            ready: true
        }
    });
});
', CClientScript::POS_HEAD);
cs()->registerCSSFile(au() . '/qtip2/jquery.qtip.css', 'screen, projection');
cs()->registerScriptFile(au() . '/qtip2/jquery.qtip.min.js', CClientScript::POS_HEAD);

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

// theme scripts
$this->renderPartial('/layouts/_theme_scripts');