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

            $post = $this->container->get('doctrine')
                ->getRepository('CCDNForumForumBundle:Post')->findByTopic($event->getTopic());

            $ultimoPost = array_pop($post);
            foreach ($post as $singlePost) {

                //Create the Transport
                $transport = \Swift_MailTransport::newInstance();

//Create the Mailer using your created Transport
                $mailer = \Swift_Mailer::newInstance($transport);

                $messaggio = \Swift_Message::newInstance()
                    ->setSubject("Creato un nuovo topic: ".$event->getTopic()->getTitle())
                    ->setFrom('info@velaforfun.com')
                    ->setTo('velaforfun@velaforfun.com')
                    ->setBcc('christian1488@hotmail.it')
                    ->setBody(
                        $this->container->get('templating')->render(
                        // app/Resources/views/Emails/registrazione.html.twig
                            'Emails/nuovo_post.html.twig',
                            array('topic' => $event->getTopic(), 'post' => $ultimoPost)
                        ),
                        'text/html'
                    );
                $mailer->send($messaggio);

            }


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




                //Create the Transport
                $transport = \Swift_MailTransport::newInstance();

//Create the Mailer using your created Transport
                $mailer = \Swift_Mailer::newInstance($transport);

                $messaggio = \Swift_Message::newInstance()
                    ->setSubject("Risposta al topic: ".$event->getTopic()->getTitle())
                    ->setFrom('info@velaforfun.com')
                    ->setTo($singlePost->getCreatedBy()->getEmail())
                    ->setBcc('christian1488@hotmail.it')
                    ->setBcc('info@velaforfun.com')
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