<?php

declare(strict_types=1);

namespace App\Services\Parser;

// https://github.com/erusev/parsedown/wiki/Tutorial:-Create-Extensions
class Convert extends \ParsedownExtraPlugin
{
    function __construct()
    {
        $this->InlineTypes['['][] = 'Spoiler';
    }

    protected function inlineSpoiler($Excerpt)
    {
        if (preg_match('/\[spoiler\](.+)\[\/spoiler]/mUs', $Excerpt['text'], $matches)) {
            return [
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => 'span',
                    'handler' => 'line',
                    'text' => $matches[1],
                    'attributes' => [
                        'class' => 'spoiler'
                    ],
                ]
            ];
        }
    }

    protected function convertYouTube($Element)
    {
        if (!$Element) return $Element;
        preg_match('/^(?:https?\:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.?be)\/(?:watch\?v=)?(.+)$/i', $Element['element']['attributes']['href'], $id);
        if (count($id)) {
            $Element['element']['name'] = 'iframe';
            $Element['element']['text'] = '';
            $Element['element']['attributes'] = [
                'width' => '576',
                'height' => '324',
                'src' => 'https://www.youtube.com/embed/' . $id[1],
                'frameborder' => '0',
                'allow' => 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture',
                'allowfullscreen' => '1'
            ];
        }
        return $Element;
    }

    protected function inlineLink($Excerpt)
    {
        $url = parent::inlineLink($Excerpt);
        return $this->convertYouTube($url);
    }

    protected function inlineUrl($Excerpt)
    {
        $url = parent::inlineUrl($Excerpt);
        return $this->convertYouTube($url);
    }
}