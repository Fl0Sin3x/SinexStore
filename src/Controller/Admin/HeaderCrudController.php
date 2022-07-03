<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Entity\Header;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre du Header'),
            TextareaField::new('content', 'Contenu du header'),
            TextField::new('btnTitle', 'Titre du bouton'),
            TextField::new('btnUrl', 'URL de destination du bouton'),
            ImageField::new('illustration')
                ->SetBasePath('/uploads')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[')
                ->setRequired(false),
        ];
    }

}
