CSMenuBundle
============

This bundle is an extension of the [KnpMenuBundle][1], which allows you to define a menu per controller action.

This is especially usefull if you want to render different menu's on each page, without having to render each menu in the view template.

Features
--------

* Define annotations on controller actions to specify which menu needs to be rendered in which block
* Create a top level menu with default values (E.G add a home link that will be available on every menu), then just define the extra menu items as necessary
* Add a menu to the controller class, to have that menu render on every action in that controller
* Used the Symfony2 event dispatcher, so you kan create a listener for any menu to extend it even further

It defines a custom provider, renderer, builder, annotation driver and twig extension

Requirements
------------

This bundle uses the KnpMenuBundle and KnpMenu library to create the menus.

Installation
------------

To install, add the following to you composer.json file:

    "require": {
        "php": ">=5.3.3",
        ....
        "customscripts/menubundle" : "dev-master"

Update composer dependencies:

    php composer.phar update
    
Then register the bundles in your AppKernel.php:

    $bundles = array(
        ....
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new CS\MenuBundle\CSMenuBundle()


Usage
--------

In order to define a menu in your controller action, include the annotatiom:

    use CS\MenuBundle\Annotation\Menu;
    
Then define the annotation on your controller class or action. The first parameter is the menu to render, then specify in which menu block it should render (E.G sidebar).
The first parameter needs to be in the following format: ````NamesapaceBundle:<class>:<method>````, where the <class> is the class located at ````AcmeDemoBundle\Menu\````, and the <method> is the method that needs to be called inside the class, appended by menu.
E.G thr parameter ````AcmeDemoBundle:Main:Sidebar```` would call the method ````sidebarMenu```` on the class ````Acme\DemoBundle\Menu\Main````

    class DefaultController extends Controller
    {
        /**
         * @Menu("AcmeDemoBundle:Main:Sidebar", block="sidebar")
         */
        public function indexAction()
        {

To display the above menu, add the following anywhere in your twig template:

    {{ cs_menu("sidebar") }}

This will render any menu defined at the sidebar block.
To display a different menu on a different action, just define a different menu to render in the annotation:

    class DefaultController extends Controller
    {
        /**
         * @Menu("AcmeDemoBundle:Edit:Sidebar", block="sidebar")
         */
        public function editAction()
        {

You can keep the ````{{ cs_menu("sidebar") }}```` in a top-level layout that you can just extend from.

To add menu items, just create the class ````Acme\DemoBundle\Menu\Main```` with the ````sidebarMenu```` method.
This method takes 2 arguments, the first is the menu builder, which can be used to add new menu items, the second is an array of all the request parameters, for when you need to include a parameter in your menu route (E.G when viewing a record, and you need to have an edit link for that record in your menu)

    namespace Acme\DemoBundle\Menu;
    
    class Main {
        public function sidebarMenu($menu, $parameters = array())
        {
            $menu->addChilde("New Link");
        }
    }


Lastly you need to create your menu builder, which is used to build the different menu blocks. With the menu builder, you can add default items (E.G a 'home' link) if you want, or specify custom attributes for the menu (E.G custom class etc).
Your builder follows the same namin convention as your menu class.
E.G to build the sidebar menu, you need to specify a ````sidebarMenu```` method, which takes 

To create a menu builder, you need to create a new service and tag it with the ````cs_menu.builder```` tag.
E.G:

    parameters:
        acme_demo.menu.builder.class: Acme\DemoBundle\Menu\Builder

    services:
            acme_demo.menu_builder
                class: %acme_demo.menu.builder.class% 
                tags:
                    - { name: cs_menu.builder }
                    
Then create your builder:

    namespace Acme\DemoBundle\Menu;
    
    class Builder {
        
        public function sidebarMenu()
        {
            $factory = $this->container->get('knp_menu.factory');

            $menu = $factory->createItem('root');

            $menu->addChild('Dashboard', array('route' => '_dashboard'));

            $menu->setChildrenAttributes(array('class' => 'nav nav-list'));

            return $menu;
        }
    }

Contributing
------------

If you wish to contribute, please fork it, make your changes, and submit a pull request.

All pull requests must conform to the standards of coding currently in the application.

If you encounter any bug or inconsitency, please submit a bug report, so we can fix it as quickly as possible.

[1]: https://github.com/KnpLabs/KnpMenuBundle
