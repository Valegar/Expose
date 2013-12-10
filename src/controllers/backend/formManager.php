<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

$formManagerController = $app['controllers_factory'];

$formManagerController->match('/{id}/edit', function (Request $request, $id) use ($app) {

    $content = new Content($app['db']);
    $dynamicForm = new DynamicForm($app['db'], $app['form.factory']);
    $fields = $content->findSectionItems($id);

    $form = $app['form.factory']->createBuilder('form')
        ->add('type', 'choice', array(
            'choices'       => DynamicForm::getFieldTypesChoice(),
            'label'         => 'form.field.type',
        ))
        ->add('title', 'text', array(
            'label'         => 'form.label',
            'attr' => array(
                'placeholder' => 'form.label',
            ),
        ))
        ->add('required', 'checkbox', array(
            'label'         => 'form.required',
            'required' => false,
        ))
        ->add('description', 'textarea', array(
            'label'         => 'form.help',
            'attr' => array(
                'placeholder' => 'form.help',
            ),
            'required' => false,
        ))
        ->add('content', 'textarea', array(
            'label'         => 'form.specs',
            'attr' => array(
                'placeholder' => 'form.specs',
            ),
            'required' => false,
        ))
        ->getForm();

    $form->handleRequest($request);
    if ($form->isValid()) {
        $data = $form->getData();
        $data['path'] = null;
        $language = 'fr';
        $content->addItem(
                $id,
                $data['type'],
                $data['path'],
                $data['title'],
                $data['description'],
                $data['content'],
                $language
        );
        return $app->redirect($app['url_generator']->generate('admin_content_manager'));
    }

    return $app['twig']->render('backend/_formEdit.html.twig', array(
        'form' => $form->createView(),
        'fields' => $fields,
        'section_id' => $id,
    ));
})
->bind('admin_form_manager_edit')
->method('GET|POST')
;

$formManagerController->post('/{id}/remove', function (Request $request, $id) use ($app) {



})
->bind('admin_form_manager_remove')
;

$formManagerController->assert('_locale', implode('|', $app['languages']));

return $formManagerController;
