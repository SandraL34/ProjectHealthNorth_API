<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* @Security/Collector/icon.svg */
class __TwigTemplate_f40d5f30c8012d861024b422394f6c9ab9c374b718f80f4844ed10a2ca2945e3 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@Security/Collector/icon.svg"));

        // line 1
        yield "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path fill=\"#AAA\" d=\"M21 20.4V22H3v-1.6c0-3.7 2.4-6.9 5.8-8-1.7-1.1-2.9-3-2.9-5.2 0-3.4 2.7-6.1 6.1-6.1s6.1 2.7 6.1 6.1c0 2.2-1.2 4.1-2.9 5.2 3.4 1.1 5.8 4.3 5.8 8z\"/></svg>
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "@Security/Collector/icon.svg";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  45 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path fill=\"#AAA\" d=\"M21 20.4V22H3v-1.6c0-3.7 2.4-6.9 5.8-8-1.7-1.1-2.9-3-2.9-5.2 0-3.4 2.7-6.1 6.1-6.1s6.1 2.7 6.1 6.1c0 2.2-1.2 4.1-2.9 5.2 3.4 1.1 5.8 4.3 5.8 8z\"/></svg>
", "@Security/Collector/icon.svg", "D:\\Logiciel\\XAMPP\\htdocs\\ProjectHealthNorth_API\\vendor\\symfony\\security-bundle\\Resources\\views\\Collector\\icon.svg");
    }
}
