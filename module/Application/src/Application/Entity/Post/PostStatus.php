<?php
namespace Application\Entity\Post;

final class PostStatus
{
	/**
	 * Publicado
	 */
	const PUBLISHED = 'published';

	/**
	 * Rascunho
	 */
	const DRAFT = 'draft';

	/**
	 * Lixo
	 */
	const TRASH = 'trash';

	/**
	 * Agendado
	 */
	const SCHEDULED = 'scheduled';

	/**
	 * Revisão pendente
	 */
	const PENDING_REVIEW = 'pending_review';

	static public function toArray() {
		return array(
			self::PUBLISHED => 'Publicado',
			self::DRAFT => 'Rascunho',
			self::PENDING_REVIEW => 'Revisão Pendente',
			self::TRASH => 'Lixeira'
		);
	}

	static public function get($op) {
		if(array_key_exists($op, self::toArray())) {
			return self::toArray()[$op];
		}
		return null;
	}
}