<?php
/**
 * EMailManager
 */
class EMailManager extends CApplicationComponent
{

    /**
     * @param $template
     * @param $viewParams
     * @return array
     */
    private function renderMessageParts($template, $viewParams)
    {
        $message = array();
        $template = str_replace('/', '.', $template);
        foreach (array('sbj', 'txt', 'htm') as $ext) {
            $contents = Yii::app()->controller->renderPartial("application.views.emails.{$template}_{$ext}", $viewParams, true);
            $message[$ext] = Yii::app()->controller->renderPartial("application.views.emails.layouts.default_{$ext}", array('contents' => $contents), true);
        }
        return $message;
    }


}