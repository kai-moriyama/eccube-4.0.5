<?php

namespace Customize\Controller\Admin\Test;

use Customize\Entity\Donut;
use Customize\Form\Type\Admin\DonutType;
use Customize\Repository\DonutRepository;
use Eccube\Controller\AbstractController;
use Eccube\Util\CacheUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Class DonutController
 */
class DonutController extends AbstractController
{

    /**
     * @var DonutRepository
     */
    protected $DonutRepository;

    /**
     * DonutController constructor.
     *
     * @param DonutRepository $DonutRepository
     */
    public function __construct(DonutRepository $donutRepository)
    {
        $this->donutRepository = $donutRepository;
    }

    /**
     * 登録情報一覧
     *
     * @Route("/%eccube_admin_route%/donut/list", name="admin_donut_list")
     * @Template("@admin/Test/donutlist.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(Request $request)
    {
        $Donut = $this->donutRepository->findBy(['del_flg' => '0'], ['id' => 'DESC']);
        //$Donut = $this->donutRepository->findAll();
        $Donuts = [];

        foreach ($Donut as $key => $value) {
            $Donuts[$value->getId()] = $value;
        }

        return [
            'Donuts' => $Donuts,
        ];
    }


    /**
     * 新規登録・編集
     * 
     * @Route("/%eccube_admin_route%/donut/new", name="admin_donut_new")
     * @Route("/%eccube_admin_route%/donut/{id}/edit", requirements={"id" = "\d+"}, name="admin_donut_edit")
     * @Template("@admin/Test/donut.twig")
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
            $Donut = new Donut();
        } else {
            $Donut = $this->donutRepository->find($id);
            if (!$Donut) {
                throw new NotFoundHttpException();
            }
        }
        //フォーム
        $builder = $this->formFactory
            ->createBuilder(DonutType::class, $Donut);
        $form = $builder->getForm();
        //POSTリクエストを受け取る
        $form->handleRequest($request);

        // 設定画面で登録or更新ボタン押下
        if ($form->isSubmitted() && $form->isValid()) {
            // フォームの入力データを取得
            $Donut = $form->getData();
            //$Donutを永続化するエンティティとして管理
            $this->entityManager->persist($Donut);
            //DBへ永続化する
            $this->entityManager->flush();

            // キャッシュの削除
            $cacheUtil->clearDoctrineCache();
            $this->addSuccess('admin.common.save_complete', 'admin');
            //新規入力画面へリダイレクト
            return $this->redirectToRoute('admin_donut_list');
        }

        // テンプレートにデータを送る
        return [
            'Donut' => $Donut,
            'form' => $form->createView(),
        ];
    }

    /**
     * 倫理削除
     * 
     * @Route("/%eccube_admin_route%/donut/{id}/remove", requirements={"id" = "\d+"}, name="admin_donut_remove", methods={"PUT"})
     */
    public function remove(Donut $Donut)
    {
        //リクエストに入っているトークンがセッションで保持しているトークンと等しいかどうかチェック
        $this->isTokenValid();
        //getしてきたdel_flgの値と反対の値をdel_flgにsetしている
        $Donut->setDelFlg(!$Donut->isDelFlg());
        //DBへ永続化する
        $this->entityManager->flush();
        //trueならば(削除できたら)削除完了flashメッセージを表示
        if ($Donut->isDelFlg()) {
            $this->addSuccess(trans('admin.common.to_logical_delete_complete', ['%name%' => $Donut->getName()]), 'admin');
        } else {
            //テスト用に設定
            $this->addSuccess('エラー', 'admin');
        }

        return $this->redirectToRoute('admin_donut_list');
    }


}