<?php

/**
 * This class print UI elements that aren't dependent on any particular form (without creating a form instance)
 */
namespace BinaryCarpenter\BC_MNC;

class BC_Static_UI
{
    /**
     * Echos an label element
     *
     * @param $field_id
     * @param string $text
     * @return string
     */
    public static function label($field_id, $text, $echo = true)
    {
        $output = sprintf('<label for="%1$s" class="bc-doc-label">%2$s</label>', $field_id, $text);
        if ($echo)
            echo $output;
        else
            return $output;
    }


    /**
     * @param string $content HTML content of the heading, usually just text
     * @param int $level heading level, similar to h1 to h6 but with smaller text. There are only three levels
     * with text size 38px, 24px and 18px
     *
     * @return string
     *
     */
    public static function heading($content, $level = 1, $echo = true)
    {

        $output = sprintf('<div class="bc-doc-heading-%1$s">%2$s</div>', $level, $content);

        if ($echo)
            echo $output;
        else
            return $output;

    }



    /**
     * @param string $content html content
     * @param string $type [error|info|warning|success]
     * @param bool $closable
     * @param bool $echo
     * @return string
     */

    public static function notice($content, $type, $closable = false, $echo = true)
    {

        switch ($type)
        {
            case 'info':
                $type_class = 'bc-uk-alert-primary';
                break;

            case 'success':
                $type_class = 'bc-uk-alert-success';
                break;

            case 'warning':
                $type_class = 'bc-uk-alert-warning';
                break;

            case 'error':
                $type_class = 'bc-uk-alert-danger';
                break;

            default:
                $type_class = 'bc-uk-alert-primary';
                break;

        }

        $closable = $closable ? '<a class="bc-uk-alert-close" bc-uk-close></a>' : '';

        $output = sprintf('<div class="%1$s" bc-uk-alert> %2$s <p>%3$s</p> </div>', $type_class, $closable, $content);

        if ($echo)
            echo $output;
        else
            return $output;

    }

    public static function flex_section($content, $flex_class = 'bc-uk-flex-left')
    {
        $html = sprintf('<div class="bc-uk-flex %1$s">', $flex_class);

        foreach ($content as $c)
            $html .= sprintf('<div>%1$s</div>', $c);

        return $html . '</div>';
    }





}