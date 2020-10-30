<?php

namespace App\Controller\BackOffice\Crud;

use App\Entity\Experience;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ExperienceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Experience::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['startDate' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')->onlyOnForms(),
            TextField::new('employmentType'),
            AssociationField::new('user')->onlyOnForms(),
            TextField::new('company'),
            UrlField::new('logo')->onlyOnForms(),
            TextField::new('location')->onlyOnForms(),
            DateField::new('startDate'),
            DateField::new('endDate'),
            TextEditorField::new('description')->onlyOnForms(),
        ];
    }
}
