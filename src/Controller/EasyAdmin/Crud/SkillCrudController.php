<?php

namespace App\Controller\EasyAdmin\Crud;

use App\Entity\Skill;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class SkillCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Skill::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $arrCategories = ['Front End', 'Back End', 'Database', 'DevOps', 'Mobile App'];

        return [
            TextField::new('name'),
            UrlField::new('logo')->onlyOnForms(),

            ChoiceField::new('category')
                ->setChoices(array_combine($arrCategories, $arrCategories))
                ->onlyOnForms(),

            TextEditorField::new('description'),
            AssociationField::new('users'),
        ];
    }
}
