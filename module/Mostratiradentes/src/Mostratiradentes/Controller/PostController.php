<?php
namespace Mostratiradentes\Controller;

use Application\Controller\SiteController;
use Application\Entity\Event\Event;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Site\SiteMeta;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part;
use Zend\View\Model\ViewModel;

class PostController extends SiteController
{
    const SITE_ID = 5;

    protected $breadcrumbs = [];

    public function indexAction()
    {
        $viewModel = new ViewModel();

        $slug = $this->params()->fromRoute('slug');
        $slug = trim($slug, '/');
        $slug = explode('/', $slug);

        $post = $this->getRepository(Post::class)->findOneBy([
            'status' => PostStatus::PUBLISHED,
            'slug' => end($slug),
            'site' => self::SITE_ID
        ]);

        if(!$post) {
            //tela de erro 404
        }

        //Custon Action
        if($post->hasMeta(SiteMeta::CUSTOM_POST_ACTION)) {
            $customAction = $post->getMeta(SiteMeta::CUSTOM_POST_ACTION);
            $customAction = explode(':', $customAction);

            return $this->forward()->dispatch($customAction[0], [
                'action' => $customAction[1],
                'post' => $post
            ]);
        }

        $viewModel->breadcrumbs = $post->getBreadcrumbs();
        $viewModel->post = $post;
        return $viewModel;
    }

    public function newsletterAction()
    {
        return [
            'breadcrumbs' => [
                ['newsletter' => 'Newsletter']
            ]
        ];
    }

    public function sitemapAction()
    {
        $this->layout("layout/xml");
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setTemplate(null);
        return $viewModel;
    }

    public function searchAction()
    {
        $post = new Post();
        $post->setTitle("Busca");
        return [
            'breadcrumbs' => [
                ['busca' => 'Busca']
            ],
            'post' => $post
        ];
    }

    public function credenciamentoAction()
    {
        $viewModel = new ViewModel();
        $post = $this->params('post');

        if($this->getRequest()->isPost()) {
            $transport = new Smtp();
            $options   = new SmtpOptions(array(
                'name' => 'smtp.universoproducao.com.br',
                'host' => 'smtp.universoproducao.com.br',
                'port'	=> '587',
                'connection_class'  => 'login',
                'connection_config' => array(
                    'username' => 'no-reply@universoproducao.com.br',
                    'password' => 'no-reply@universo'
                ),
            ));
            $transport->setOptions($options);

            $postData = $this->getRequest()->getPost();

            $nome							= $postData['NomeVeiculo'];
            $endereco 						= $postData['Endereco'];
            $website 						= $postData['Website'];
            $cidade 						= $postData['Cidade'];
            $uf 							= $postData['UF'];
            $cep 							= $postData['CEP'];
            $tipo 							= $postData['Tipo'];
            $tipoOutros 					= $postData['TipoOutros'];
            $cobertura 						= $postData['Cobertura'];
            $periodicidade 					= $postData['Periodicidade'];
            $periodicidadeOutros			= $postData['PeriodicidadeOutros'];
            $alcance 						= $postData['alcance'];
            $anoFundacao 					= $postData['anoFundacao'];
            $editoria 						= $postData['editoria'];
            $nomeRedatorChefe 				= $postData['NomeRedatorChefe'];
            $nomeResponsavelCredenciamento 	= $postData['NomeResponsavelCredenciamento'];
            $cargo 							= $postData['Cargo'];
            $telefoneFixo 					= $postData['TelefoneFixo'];
            $telefoneCelular 				= $postData['TelefoneCelular'];
            $email 							= $postData['email'];
            $perCobertura 					= $postData['perCobertura'];
            $coberturaParte					= $postData['coberturaParte'];
            $TipoCorbeturaJustificativa		= $postData['TipoCorbeturaJustificativa'];
            $Foco 							= $postData['Foco'];
            $FocoOutros 					= $postData['FocoOutros'];
            $publicacoes 					= $postData['publicacoes'];
            $PeriodoPublicacao 				= $postData['PeriodoPublicacao'];
            $dataLimiteDuranteApos 			= $postData['dataLimiteDuranteApos'];
            $dataLimiteApos 				= $postData['dataLimiteApos'];
            $equipeCobertura 				= $postData['EquipeCorbetura'];
            $logistica		 				= $postData['logistica'];

            //Envia a mensagem de confirmação para a pessoa cadastrada no formulario
            $msgConfirmacaoCadastro = "Informamos que sua solicitação de credenciamento de imprensa para a 20ª Mostra Tiradentes foi enviada com sucesso.";
            $msgConfirmacaoCadastro .= "<br /><br />";
            $msgConfirmacaoCadastro .= "Em breve a equipe da assessoria entrará em contato.";
            $msgConfirmacaoCadastro .= "<br /><br />";
            $msgConfirmacaoCadastro .= "Qualquer dúvida, estamos à disposição pelo telefone (31) 3282 2366 ou e-mail imprensa@universoproducao.com.br";
            $msgConfirmacaoCadastro .= "<br /><br />";
            $msgConfirmacaoCadastro .= "Atenciosamente,<br />";
            $msgConfirmacaoCadastro .= "Equipe Imprensa – CineOP<br />";
            $msgConfirmacaoCadastro .= "www.cinebh.com.br<br />";

            $htmlMessage = new Part($msgConfirmacaoCadastro);
            $htmlMessage->type = 'text/html';
            $miniMessage = new \Zend\Mime\Message();
            $miniMessage->setParts(array($htmlMessage));

            $mailConfirmacao = new Message();
            $mailConfirmacao->setBody($miniMessage);
            $mailConfirmacao->setFrom('no-reply@universoproducao.com.br', 'Universo Producao');
            $mailConfirmacao->setTo($email);
            $mailConfirmacao->setSubject('Solicitação de credenciamento – 20a Mostra Tiradentes');
            $transport->send($mailConfirmacao);

            $msgFormulario  = "<h1>FORMUL&Aacute;RIO DE CREDENCIAMENTO</h1>";
            $msgFormulario .= "<table width='540' border='1' cellspacing='0' cellpadding='2' class='tabelaForm'>";
            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Nome do Ve&iacute;culo:</td>";
            $msgFormulario .= "<td>".$nome."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Endere&ccedil;o:</td>";
            $msgFormulario .= "<td>".$endereco."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Website:</td>";
            $msgFormulario .= "<td>".$website."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Cidade:</td>";
            $msgFormulario .= "<td>".$cidade;
            $msgFormulario .= "&nbsp;&nbsp;UF:";
            $msgFormulario .= $uf."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Cep:</td>";
            $msgFormulario .= "<td>".$cep."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Tipo: </td>";
            $msgFormulario .= "<td>".$tipo." | ".$tipoOutros."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Cobertura:</td>";
            $msgFormulario .= "<td>".$cobertura."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Periodicidade:</td>";
            $msgFormulario .= "<td>".$periodicidade." | ".$periodicidadeOutros."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= " <td width='21%' align='right'>Alcance/Tiragem:</td>";
            $msgFormulario .= "<td width='79%'>".$alcance."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= " <td width='21%' align='right'>Ano de Funda&ccedil;&atilde;o:</td>";
            $msgFormulario .= "<td width='79%'>".$anoFundacao."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= " <td width='21%' align='right'>Editoria:</td>";
            $msgFormulario .= "<td width='79%'>".$editoria."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= " <td width='21%' align='right'>Nome do redator chefe:</td>";
            $msgFormulario .= "<td width='79%'>".$nomeRedatorChefe."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Nome do respons&aacute;vel pelo credenciamento:</td>";
            $msgFormulario .= "<td>".$nomeResponsavelCredenciamento."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Cargo:</td>";
            $msgFormulario .= "<td>".$cargo."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Telefone fixo do ve&iacute;culo:</td>";
            $msgFormulario .= "<td>".$telefoneFixo."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Telefone celular:</td>";
            $msgFormulario .= "<td>".$telefoneCelular."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "Email:";
            $msgFormulario .= "<td>".$email."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Per&iacute;odo de Cobertura:</td>";
            $msgFormulario .= "<td>".$perCobertura;
            $msgFormulario .= "&nbsp;|&nbsp;";
            $msgFormulario .= $coberturaParte."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right' valign='top'>Proposta de Cobertura - Justificativa:</td>";
            $msgFormulario .= "<td>".$TipoCorbeturaJustificativa."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right' valign='top'>Proposta de Cobertura - Foco Principal:</td>";
            $msgFormulario .= "<td>".$Foco." | ".$FocoOutros."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>N&uacute;mero de Publica&ccedil;&otilde;es/Inser&ccedil;&otilde;es previstas:</td>";
            $msgFormulario .= "<td>".$publicacoes."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right'>Per&iacute;odo de publica&ccedil;&otilde;es/inser&ccedil;&otilde;es:</td>";
            $msgFormulario .= "<td>".$PeriodoPublicacao." | ".$dataLimiteDuranteApos." ".$dataLimiteApos."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right' valign='top'>Equipe de cobertura<br />nomes e fun&ccedil;&otilde;es:</td>";
            $msgFormulario .= "<td>".$equipeCobertura."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario .= "<tr>";
            $msgFormulario .= "<td align='right' valign='top'>Logistica:</td>";
            $msgFormulario .= "<td>".$logistica."</td>";
            $msgFormulario .= "</tr>";

            $msgFormulario = utf8_decode($msgFormulario);

            $htmlMessage = new Part($msgFormulario);
            $htmlMessage->type = 'text/html';
            $miniMessage = new \Zend\Mime\Message();
            $miniMessage->setParts(array($htmlMessage));

            $mail = new Message();
            $mail->setBody($miniMessage);
            $mail->setFrom('no-reply@universoproducao.com.br', 'Universo Producao');
            $mail->setTo('imprensa@universoproducao.com.br');
            $mail->addBcc('ricardovianasi@gmail.com');
            $mail->setSubject('Credenciamento de Imprensa - CineBH');

            try {
                $transport->send($mail);
                $viewModel->credenciamento = true;
            } catch (\Exception $e) {
                ///return $this->forward()
            }
        }

        $viewModel->post = $post;
        $viewModel->breadcrumbs = $post->getBreadcrumbs();

        return $viewModel;
    }

    public function edicoesAnterioresAction()
    {
        $viewModel = new ViewModel();
        $post = $this->params('post');

        $events = $this->getRepository(Event::class)->findBy([
            'type' => 'cinebh'
        ], ['edition' => 'DESC']);

        $viewModel->post = $post;
        $viewModel->breadcrumbs = $post->getBreadcrumbs();
        $viewModel->events = $events;

        return $viewModel;
    }
}
