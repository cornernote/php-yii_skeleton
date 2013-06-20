<?php
class FormHelper
{
    /**
     * @static
     * @param $id
     */
    public static function searchToggle($id)
    {
        $showSearch = sf('showSearch') ? "$('.search-button').click();" : '';
        cs()->registerScript($id . '-search', "
            $(document).ready(function(){
                $('.search-button').click(function(){
                    $('.search-form').toggle();
                });
                $showSearch
                $('.search-form form').submit(function(){
                    $.fn.yiiGridView.update('$id', {
                        url: $(this).attr('action'),
                        data: $(this).serialize()
                    });
                    return false;
                });
            });
        ");
    }
}