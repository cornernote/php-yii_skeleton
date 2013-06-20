<?php
/**
 * Override CFormModel
 */
class FormModel extends CFormModel
{

    /**
     * @return string
     */
    public function getErrorString()
    {
        $output = '';
        foreach ($this->getErrors() as $attribute => $errors) {
            $output .= $attribute . ': ' . implode(' ', $errors) . ' | ';
        }
        return $output;
    }


}
