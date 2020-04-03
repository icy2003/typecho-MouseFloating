<?php
/**
 * 鼠标漂浮字插件
 *
 * @package MouseFloating
 * @author icy2003
 * @version 1.0.0
 * @link https://github.com/icy2003/typecho-MouseFloating
 */
class MouseFloating_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->footer = array('MouseFloating_Plugin', 'footer');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {}

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /** 开始时间 */
        $floatingWords = new Typecho_Widget_Helper_Form_Element_Text(
            'floatingWords', null, '富强,民主,文明,和谐,自由,平等,公正,法治,爱国,敬业,诚信,友善',
            _t('漂浮字内容'),
            _t('将用英文逗号（,）分割成多条文字随机出现')
        );
        $form->addInput($floatingWords);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {}

    /**
     * 底部输出
     *
     * @access public
     * @return void
     */
    public static function footer()
    {
        Typecho_Widget::widget('Widget_Options')->to($options);
        $settings = $options->plugin('MouseFloating');
        ?>
<script>
    // 鼠标漂浮字
    $(document).click(function(e) {
        var list = <?php echo json_encode(explode(',', $settings->floatingWords)); ?>;
        textUp(e, 2000, list, 200);
    })

    function textUp(e, time, arr, heightUp) {
        var lists = Math.floor(Math.random() * arr.length);
        var colors = '#' + Math.floor(Math.random() * 0xffffff).toString(16);
        var $i = $('<span />').text(arr[lists]);
        var xx = e.pageX || e.clientX + document.body.scroolLeft;
        var yy = e.pageY || e.clientY + document.body.scrollTop;

        $('body').append($i);
        $i.css({
            'font-family': '"Microsoft JhengHei","Apple LiGothic Medium,Microsoft YaHei","微软雅黑","Arial",sans-serif',
            'font-size': '20px',
            'font-weight': 'bold',
            cursor: 'default',
            top: yy,
            left: xx,
            color: colors,
            transform: 'translate(-50%, -50%)',
            position: 'absolute',
            zIndex: 999999999999
        })
        $i.animate({
            top: yy - (heightUp ? heightUp : 200),
            opacity: 0
        }, time, function() {
            $i.remove();
        })
    }
    </script>
<?php
}
}

?>