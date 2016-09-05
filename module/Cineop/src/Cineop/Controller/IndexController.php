<?php
namespace Cineop\Controller;

use Application\Entity\Banner\Banner;
use Application\Entity\Event\Event;
use Application\Entity\Gallery\Gallery;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Post\Post;
use Application\Entity\Programation\Highlight;
use Application\Entity\Tv\Tv;
use Util\Controller\AbstractController;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
	public function indexAction()
	{
		$banners = $this->getRepository(Banner::class)->findBy([
			'site' => 2
		], ['order'=>'ASC']);

		$news = $this->getRepository(Post::class)->findBy([
			'type' => PostType::NEWS,
			'status' => PostStatus::PUBLISHED
		], ['postDate'=>'DESC'], 3);

		$guides = $this->getRepository(Post::class)->findBy([
			'type' => PostType::GUIDE,
			'status' => PostStatus::PUBLISHED
		], ['order'=>'ASC'], 4);

		$gallery = $this->getRepository(Gallery::class)->findBy([
			'site' => 2
		], ['order'=>'ASC'], 5);

		$video = $this->getRepository(Tv::class)->findOneBy(['site' => 2], [
			'date' => 'DESC'
		]);

		$program = $this->getRepository(Highlight::class)->findBy([
			'site' => 2,
			'isHighlight' => 0,
		], ['position'=>'ASC']);

		$programHighlight = $this->getRepository(Highlight::class)->findOneBy([
			'site' => 2,
			'isHighlight' => 1,
		]);

		$lastDaysVideos = $this
			->getRepository(Tv::class)
			->createQueryBuilder('t')
			->andWhere('t.site = 2')
			->orderBy('t.date', 'DESC')
			->setMaxResults(6)
			->getQuery()
			->getResult();

		return [
			'banners' => $banners,
			'news' => $news,
			'guides' => $guides,
			'gallery' => $gallery,
			'video' => $video,
			'lastDaysVideos' => $lastDaysVideos,
			'programns' => $program,
			'programHighLight' => $programHighlight
		];
	}

	public function postAction()
	{
		$viewModel = new ViewModel();

		$slug = $this->params()->fromRoute('slug');
		$slug = rtrim($slug, '/');
		$slug = explode('/', $slug);

		$post = $this->getRepository(Post::class)->findOneBy([
			'status' => PostStatus::PUBLISHED,
			'slug' => end($slug)
		]);

		if(!$post) {
			//tela de erro 404
		}


		if(end($slug) == 'credenciamento') {
			$viewModel->setTemplate('cineop/index/credenciamento.phtml');

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
				$msgConfirmacaoCadastro = "Informamos que sua solicitação de credenciamento de imprensa para a 11ª CineOP foi enviada com sucesso.";
				$msgConfirmacaoCadastro .= "<br /><br />";
				$msgConfirmacaoCadastro .= "Em breve a equipe da assessoria entrará em contato.";
				$msgConfirmacaoCadastro .= "<br /><br />";
				$msgConfirmacaoCadastro .= "Qualquer dúvida, estamos à disposição pelo telefone (31) 3282 2366 ou e-mail imprensa@universoproducao.com.br";
				$msgConfirmacaoCadastro .= "<br /><br />";
				$msgConfirmacaoCadastro .= "Atenciosamente,<br />";
				$msgConfirmacaoCadastro .= "Equipe Imprensa – CineOP<br />";
				$msgConfirmacaoCadastro .= "www.cineop.com.br<br />";

				$htmlMessage = new Part($msgConfirmacaoCadastro);
				$htmlMessage->type = 'text/html';
				$miniMessage = new \Zend\Mime\Message();
				$miniMessage->setParts(array($htmlMessage));

				$mailConfirmacao = new Message();
				$mailConfirmacao->setBody($miniMessage);
				$mailConfirmacao->setFrom('no-reply@universoproducao.com.br', 'Universo Producao');
				$mailConfirmacao->setTo($email);
				$mailConfirmacao->setSubject('Solicitação de credenciamento – 11a CineOP');
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
				$mail->setSubject('Credenciamento de Imprensa - CineOP');

				try {
					$transport->send($mail);
					$viewModel->credenciamento = true;
				} catch (\Exception $e) {
					///return $this->forward()
				}
			}

		} elseif(end($slug) == 'noticias') {
			$viewModel->setTemplate('cineop/index/news-list.phtml');
			$newsList = $this->getRepository(Post::class)->findBy([
				'status' => PostStatus::PUBLISHED,
				'type' => PostType::NEWS
			]);
			$viewModel->listNews = $newsList;
		} elseif (end($slug) == 'programacao') {
			$viewModel->setTemplate('cineop/index/programacao.phtml');
		} elseif(end($slug) == 'filmes') {
			$viewModel->setTemplate('cineop/index/filmes.phtml');
		} elseif($slug[0] == 'filmes' && is_numeric(end($slug))) {
			$viewModel->setTemplate('cineop/index/filmedetalhe.phtml');
			$viewModel->CodFilme = end($slug);
		} elseif($slug[0] == 'seminarios' && is_numeric(end($slug))) {
			$viewModel->setTemplate('cineop/index/seminario-detalhe.phtml');
		} elseif(end($slug) == 'seminarios') {
			$viewModel->setTemplate('cineop/index/seminarios.phtml');
		}  elseif($slug[0] == 'arte' && is_numeric(end($slug))) {
			$viewModel->setTemplate('cineop/index/arte-detalhe.phtml');
		} elseif(end($slug) == 'arte') {
			$viewModel->setTemplate('cineop/index/arte.phtml');
		} elseif(end($slug) == 'newsletter') {
			$viewModel->setTemplate('cineop/index/newsletter.phtml');
		} elseif($slug[0] == 'edicoes-anteriores' && is_numeric(end($slug))) {
			//$viewModel->setTemplate('cineop/index/filmedetalhe.phtml');
			$mostra = $this->getRepository(Event::class)->findOneBy([
				'edition' => end($slug)
			]);

			$post = new Post();
			$post->setTitle($mostra->getFullName());
			$post->setContent($mostra->getDescription());
		} elseif(end($slug) == 'edicoes-anteriores') {
			$mostras = $this->getRepository(Event::class)->findBy([
				'type' => 'cineop'
			]);
			$viewModel->setTemplate('cineop/index/mostra-list.phtml');
			$viewModel->edicoes = $mostras;
		} elseif((end($slug) == 'tv-mostra') || ($slug['0'] == 'tv-mostra' && !empty(end($slug)))) {

			$where = ['site' => 2];
			if($slug[0] == 'tv-mostra' && !empty(end($slug))) {
				$date = end($slug);
				$date = \DateTime::createFromFormat('d-m-Y', $date);
				if($date) {
					$where['date'] = $date;
				}
			}

			$videos = $this->getRepository(Tv::class)->findBy($where, ['date' => 'DESC']);
			$viewModel->videos = $videos;
			$viewModel->setTemplate('cineop/index/tv-mostra.phtml');
		}

		$viewModel->setVariables(['post' => $post]);
		return $viewModel;
	}
}