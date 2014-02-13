<?php

namespace Ideys\Content\Section;

use Ideys\Content\Item\Video;
use Ideys\Content\ContentInterface;
use Symfony\Component\Form\FormFactory;

/**
 * Channel content manager.
 */
class Channel extends Section implements ContentInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getParameters()
    {
        return array();
    }

    /**
     * Add form
     */
    public function addForm(FormFactory $formFactory, Video $video)
    {
        $formBuilder = $formFactory
            ->createBuilder('form', $video)
            ->add('category', 'choice', array(
                'label' => 'channel.provider.choice',
                'choices' => static::getProviderChoice(),
            ))
            ->add('title', 'text', array(
                'label' => 'channel.video.title',
            ))
            ->add('content', 'textarea', array(
                'label' => 'channel.video.code',
            ))
        ;

        return $formBuilder->getForm();
    }

    /**
     * @return array
     */
    public static function getProviderChoice()
    {
        return array(
            Video::PROVIDER_VIMEO => ucfirst(Video::PROVIDER_VIMEO),
            Video::PROVIDER_DAILYMOTION => ucfirst(Video::PROVIDER_DAILYMOTION),
            Video::PROVIDER_YOUTUBE => ucfirst(Video::PROVIDER_YOUTUBE),
        );
    }

    public function guessVideoCode(Video $video)
    {
        switch (true) {
            case (strpos($video->content, Video::PROVIDER_VIMEO) > -1) :
                $video->category = Video::PROVIDER_VIMEO;
                //http://vimeo.com/12345678
                if (strpos($video->content, 'http') === 0) {
                    $video->path = '//player.vimeo.com/video/'.filter_var($video->content, FILTER_SANITIZE_NUMBER_INT);
                    break;
                }
                //<iframe src="//player.vimeo.com/video/12345678?title=0&amp;byline=0&...
                $videoSrc = $this->extractIframeSrc($video->content);
                $video->path = preg_filter('!([^\?]+)(.*)?!', '$1', $videoSrc);
            break;

            case (strpos($video->content, Video::PROVIDER_DAILYMOTION) > -1) :
                $video->category = Video::PROVIDER_DAILYMOTION;
                //http://www.dailymotion.com/video/abc12ef_lorem-ipsum...
                if (strpos($video->content, 'http') === 0) {
                    $parse = explode('video/', $video->content);
                    $code = preg_filter('!([^_]+)(.*)?!', '$1', $parse[1]);
                    $video->path = 'http://www.dailymotion.com/embed/video/'.$code;
                    break;
                }
                //<iframe frameborder="0" src="http://www.dailymotion.com/embed/video/abc12ef
                $video->path = $this->extractIframeSrc($video->content);
            break;

            case (strpos($video->content, Video::PROVIDER_YOUTUBE) > -1) :
                $video->category = Video::PROVIDER_YOUTUBE;
                //http://www.youtube.com/watch?v=ABC_123...
                if (strpos($video->content, 'http') === 0) {
                    $parse = explode('?v=', $video->content);
                    $code = preg_filter('!([^&]+)(.*)?!', '$1', $parse[1]);
                    $video->path = '//www.youtube.com/embed/'.$code;
                    break;
                }
                //<iframe width="960" height="720" src="//www.youtube.com/embed/ABC_123...
                $video->path = $this->extractIframeSrc($video->content);
            break;
        }
    }

    /**
     * Extract src attribute from an iframe HTML tag.
     *
     * @param string $content
     */
    private function extractIframeSrc($content)
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($content);

        $iframe = $dom->getElementsByTagName('iframe');

        return $iframe->item(0)->getAttribute('src');
    }
}
