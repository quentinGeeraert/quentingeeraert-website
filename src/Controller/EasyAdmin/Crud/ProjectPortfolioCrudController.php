<?php

namespace App\Controller\EasyAdmin\Crud;

use App\Entity\ProjectPortfolio;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ProjectPortfolioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectPortfolio::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setDefaultSort(['created_at' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $arrCategories = [
            'learning', 'api', 'management', 'backend',
            'design', 'laravel', 'symfony', 'cms',
        ];

        return [
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextEditorField::new('description'),

            CollectionField::new('pictures')->onlyOnForms(),

            ChoiceField::new('categories')
                ->allowMultipleChoices()
                ->setChoices(array_combine($arrCategories, $arrCategories))
                ->onlyOnForms(),

            TextEditorField::new('html_content')
                ->setFormType(CKEditorType::class),

            BooleanField::new('is_online'),
        ];
    }
}
