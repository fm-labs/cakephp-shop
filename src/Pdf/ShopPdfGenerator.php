<?php
declare(strict_types=1);

namespace Shop\Pdf;

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\Plugin;
use Tcpdf\Lib\CakeTcpdf;
use Tcpdf\View\PdfView;

/**
 * Class InvoicePdfGenerator
 *
 * @package Shop\Lib
 */
class ShopPdfGenerator
{
    use InstanceConfigTrait;

    protected array $_defaultConfig = [
        'title' => 'document',
        'subject' => '',
        'keywords' => '',
        'author' => 'CakePHP Shop',
        'creator' => 'CakePHP Shop',
    ];

    protected $_engineClass;

    /**
     * @var \Tcpdf\View\PdfView
     */
    protected PdfView $view;

    protected string $plugin = "Shop";
    protected string $layout = "Shop.print";
    protected string $templatePath = "Admin/ShopOrders";
    protected string $template = "Shop.printview";

    /**
     * Constructor
     */
    public function __construct($config = [])
    {
        $this->setConfig($config);

        if (!Plugin::isLoaded('Tcpdf')) {
            throw new MissingPluginException(['plugin' => 'Tcpdf']);
        }
        $this->_engineClass = Configure::read('Shop.Pdf.engine', CakeTcpdf::class);

        $view = new PdfView();
        $view->setPlugin($this->plugin);
        $view->setLayout($this->layout);
        $view->setTemplatePath($this->templatePath);
        $view->setTemplate($this->template);
        $this->view = $view;
    }

    public function setViewVars($vars): ShopPdfGenerator
    {
        $this->view->set($vars);
        return $this;
    }

    public function setLayout($name): ShopPdfGenerator
    {
        $this->view->setLayout($name);
        return $this;
    }

    public function setTemplatePath($name): ShopPdfGenerator
    {
        $this->view->setTemplatePath($name);
        return $this;
    }

    public function setTemplate($name): ShopPdfGenerator
    {
        $this->view->setTemplate($name);
        return $this;
    }

    /**
     * @param null $orderId
     * @param array $pdf
     */
    public function generate(array $vars = [], array $pdf = [])
    {
        $this->setViewVars($vars);
        $this->render($pdf);
    }

    /**
     * @throws \Exception
     */
    protected function render($options = [])
    {
        $pdf = array_merge([
//            'title' => $this->title,
//            'subject' => $this->subject,
//            'keywords' => $this->keywords,
            'output' => 'F',
            'filename' => null,
        ], $this->getConfig(), $options);

        $this->view->set('pdf', $pdf);
        $this->view->set('pdfEngine', $this->_engineClass);
        $this->view->render();
    }

    public function getView(): PdfView
    {
        return $this->view;
    }
}
