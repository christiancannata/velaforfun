<?php
namespace AppBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent;

class VelaForumListener implements EventSubscriberInterface
{

    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }


    /**
     *
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ForumEvents::USER_TOPIC_CREATE_COMPLETE => 'onTopicCreateComplete',
            ForumEvents::USER_TOPIC_REPLY_COMPLETE => 'onTopicReplyComplete',
        );
    }


    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent $event
     */
    public function onTopicCreateComplete(UserTopicEvent $event)
    {
        if ($event->getTopic()) {

        }
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent $event
     */
    public function onTopicReplyComplete(UserTopicEvent $event)
    {
        if ($event->getTopic()) {

            $post = $this->container->get('doctrine')
                ->getRepository('CCDNForumForumBundle:Post')->findByTopic($event->getTopic());

            $ultimoPost = array_pop($post);
            foreach ($post as $singlePost) {

                $mailer = $this->container->get('mailer');
                $messaggio = $mailer->createMessage()
                    ->setSubject("Risposta al topic: ".$event->getTopic()->getTitle())
                    ->setFrom('info@velaforfun.com')
                    ->setTo($singlePost->getCreatedBy()->getEmail())
                    ->setBcc('christian1488@hotmail.it')
                    ->setBody(
                        $this->container->get('templating')->render(
                        // app/Resources/views/Emails/registrazione.html.twig
                            'Emails/risposta_post.html.twig',
                            array('topic' => $event->getTopic(), 'post' => $ultimoPost)
                        ),
                        'text/html'
                    );
                $mailer->send($messaggio);

            }


        }
    }

}