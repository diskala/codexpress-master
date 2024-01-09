<?php

namespace App\Controller\Admin;

use App\Entity\Snippet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SnippetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Snippet::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
