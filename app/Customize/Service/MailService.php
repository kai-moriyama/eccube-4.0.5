<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Service;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\BaseInfo;
use Eccube\Entity\Customer;
use Eccube\Entity\MailHistory;
use Eccube\Entity\MailTemplate;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Shipping;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\MailHistoryRepository;
use Eccube\Repository\MailTemplateRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Eccube\Entity\CustomerAddress;
use Eccube\Service as Service;

class MailService extends Service\MailService
{

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var MailTemplateRepository
     */
    protected $mailTemplateRepository;

    /**
     * @var MailHistoryRepository
     */
    private $mailHistoryRepository;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var BaseInfo
     */
    protected $BaseInfo;

    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * MailService constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param MailTemplateRepository $mailTemplateRepository
     * @param MailHistoryRepository $mailHistoryRepository
     * @param BaseInfoRepository $baseInfoRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param \Twig_Environment $twig
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        \Swift_Mailer $mailer,
        MailTemplateRepository $mailTemplateRepository,
        MailHistoryRepository $mailHistoryRepository,
        BaseInfoRepository $baseInfoRepository,
        EventDispatcherInterface $eventDispatcher,
        \Twig_Environment $twig,
        EccubeConfig $eccubeConfig
    ) {
        $this->mailer = $mailer;
        $this->mailTemplateRepository = $mailTemplateRepository;
        $this->mailHistoryRepository = $mailHistoryRepository;
        $this->BaseInfo = $baseInfoRepository->get();
        $this->eventDispatcher = $eventDispatcher;
        $this->eccubeConfig = $eccubeConfig;
        $this->twig = $twig;
    }



    /**
     * Mypageから新規お届け先を追加した場合に送信されるメール。
     *
     * @param $Customer 会員情報
     * @param $CustomerAddress お届け先情報
     * 
     * @return \Swift_Message
     */
    public function sendNewDeliveryNotificationMail(\Eccube\Entity\Customer $Customer, \Eccube\Entity\CustomerAddress $CustomerAddress)
    {
        log_info('新規お届け先追加メール送信開始');

        // dtb_mail_templateの情報を取得
        $MailTemplate = $this->mailTemplateRepository->find($this->eccubeConfig['eccube_delivery_new_notification_mail_template_id']);

        // $MailTemplateのテンプレートのbody部分に入るそれぞれの値を代入
        $body = $this->twig->render($MailTemplate->getFileName(), [
            'Customer' => $Customer,
            'CustomerAddress' => $CustomerAddress,
            'BaseInfo' => $this->BaseInfo,
        ]);

        // 件名とかメールアドレスの値
        $message = (new \Swift_Message())
            ->setSubject('[' . $this->BaseInfo->getShopName() . '] ' . $MailTemplate->getMailSubject())
            ->setFrom(array($this->BaseInfo->getEmail01() => $this->BaseInfo->getShopName()))
            ->setTo([$this->BaseInfo->getEmail02()]);

        // HTMLテンプレートが存在する場合
        $htmlFileName = $this->getHtmlTemplate($MailTemplate->getFileName());
        if (!is_null($htmlFileName)) {
            $htmlBody = $this->twig->render($htmlFileName, [
                'Customer' => $Customer,
                'CustomerAddress' => $CustomerAddress,
                'BaseInfo' => $this->BaseInfo,
            ]);

            $message
                ->setContentType('text/plain; charset=UTF-8')
                ->setBody($body, 'text/plain')
                ->addPart($htmlBody, 'text/html');
        } else {
            $message->setBody($body);
        }

        $count = $this->mailer->send($message);

        log_info('新規お届け先追加メール送信終了', ['count' => $count]);

        return $message;
        //return $count;
    }
}