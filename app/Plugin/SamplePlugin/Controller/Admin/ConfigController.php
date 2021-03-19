<?php

namespace Plugin\SamplePlugin\Controller\Admin;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Plugin\SamplePlugin\Entity\SamplePluginConfig;
use Plugin\SamplePlugin\Form\Type\Admin\SamplePluginConfigType;
use Plugin\SamplePlugin\Repository\SamplePluginConfigRepository;
use Eccube\Util\CacheUtil;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class ConfigController extends AbstractController
{
    /**
     * @var SamplePluginConfigRepository
     */
    protected $configRepository;

    /**
     * ConfigController constructor.
     *
     * @param SamplePluginConfigRepository $configRepository
     */
    public function __construct(SamplePluginConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    // /**
    //  * @Route("/%eccube_admin_route%/sample_plugin/config", name="sample_plugin_admin_config")
    //  * @Template("@SamplePlugin/admin/config.twig")
    //  */
    // public function index(Request $request)
    // {
    //     $Config = $this->configRepository->get();
    //     $form = $this->createForm(ConfigType::class, $Config);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $Config = $form->getData();
    //         $this->entityManager->persist($Config);
    //         $this->entityManager->flush($Config);
    //         $this->addSuccess('登録しました。', 'admin');

    //         return $this->redirectToRoute('sample_plugin_admin_config');
    //     }

    //     return [
    //         'form' => $form->createView(),
    //     ];
    // }

    /**
     * 登録情報一覧
     *
     * @Route("/%eccube_admin_route%/sample_plugin/config", name="sample_plugin_admin_config")
     * @Template("@SamplePlugin/admin/config.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(Request $request)
    {
        $Sample = $this->configRepository->findBy(['del_flg' => '0'], ['id' => 'DESC']);
        //$Donut = $this->configRepository->findAll();
        $Samples = [];

        foreach ($Sample as $key => $value) {
            $Samples[$value->getId()] = $value;
        }

        return [
            'Samples' => $Samples,
        ];
    }


    /**
     * 新規登録・編集
     * 
     * @Route("/%eccube_admin_route%/sample_plugin/new", name="sample_plugin_admin_new")
     * @Route("/%eccube_admin_route%/sample_plugin/{id}/edit", requirements={"id" = "\d+"}, name="sample_plugin_admin_edit")
     * @Template("@SamplePlugin/admin/sample_edit.twig")
     * 
     * @param Request $request
     * 
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * 
     */
    public function edit(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        //新規登録
        if (is_null($id)) {
            $Sample = new SamplePluginConfig();
        } else {
            $Sample = $this->configRepository->find($id);
            if (!$Sample) {
                throw new NotFoundHttpException();
            }
        }
        //フォーム
        $builder = $this->formFactory
            ->createBuilder(SamplePluginConfigType::class, $Sample);
        $form = $builder->getForm();
        //POSTリクエストを受け取る
        $form->handleRequest($request);

        // 設定画面で登録or更新ボタン押下
        if ($form->isSubmitted() && $form->isValid()) {
            // フォームの入力データを取得
            $Sample = $form->getData();
            //$Sampleを永続化するエンティティとして管理
            $this->entityManager->persist($Sample);
            //DBへ永続化する
            $this->entityManager->flush();

            // キャッシュの削除
            $cacheUtil->clearDoctrineCache();
            $this->addSuccess('admin.common.save_complete', 'admin');
            //新規入力画面へリダイレクト
            return $this->redirectToRoute('sample_plugin_admin_config');
        }

        // テンプレートにデータを送る
        return [
            'Sample' => $Sample,
            'form' => $form->createView(),
        ];
    }

    /**
     * 倫理削除
     * 
     * @Route("/%eccube_admin_route%/sample_plugin/{id}/remove", requirements={"id" = "\d+"}, name="sample_plugin_admin_remove", methods={"PUT"})
     * 
     * 
     */
    public function remove(SamplePluginConfig $SamplePluginConfig)
    {
        //リクエストに入っているトークンがセッションで保持しているトークンと等しいかどうかチェック
        $this->isTokenValid();
        //getしてきたdel_flgの値と反対の値をdel_flgにsetしている
        $SamplePluginConfig->setDelFlg(!$SamplePluginConfig->isDelFlg());
        //DBへ永続化する
        $this->entityManager->flush();
        //trueならば(削除できたら)削除完了flashメッセージを表示
        if ($SamplePluginConfig->isDelFlg()) {
            $this->addSuccess(trans('admin.common.to_logical_delete_complete', ['%name%' => $SamplePluginConfig->getName()]), 'admin');
        } else {
            //テスト用に設定
            $this->addSuccess('エラー', 'admin');
        }

        return $this->redirectToRoute('sample_plugin_admin_config');
    }

}
