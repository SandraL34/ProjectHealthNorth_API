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

/* invoice/pdf.html.twig */
class __TwigTemplate_6eb7fc157d56b42021175cb3027b96e1a2d79f07f66bf0f85e5a24a2535abf28 extends Template
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
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "invoice/pdf.html.twig"));

        // line 1
        yield "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <style>
        body { font-family: Arial; }
        h1 { text-align: center; }
        .box { margin-top: 20px; }
    </style>
</head>
<body>

<h1>Facture</h1>

<div class=\"box\">
    <p><strong>Facture #";
        // line 16
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["invoice"]) || array_key_exists("invoice", $context) ? $context["invoice"] : (function () { throw new RuntimeError('Variable "invoice" does not exist.', 16, $this->source); })()), "id", [], "any", false, false, false, 16), "html", null, true);
        yield "</strong></p>

    ";
        // line 18
        if (CoreExtension::getAttribute($this->env, $this->source, (isset($context["invoice"]) || array_key_exists("invoice", $context) ? $context["invoice"] : (function () { throw new RuntimeError('Variable "invoice" does not exist.', 18, $this->source); })()), "appointment", [], "any", false, false, false, 18)) {
            // line 19
            yield "        <p>Date : ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["invoice"]) || array_key_exists("invoice", $context) ? $context["invoice"] : (function () { throw new RuntimeError('Variable "invoice" does not exist.', 19, $this->source); })()), "appointment", [], "any", false, false, false, 19), "date", [], "any", false, false, false, 19), "d/m/Y"), "html", null, true);
            yield "</p>
        <p>
            Patient :
            ";
            // line 22
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["invoice"]) || array_key_exists("invoice", $context) ? $context["invoice"] : (function () { throw new RuntimeError('Variable "invoice" does not exist.', 22, $this->source); })()), "appointment", [], "any", false, false, false, 22), "patient", [], "any", false, false, false, 22), "firstname", [], "any", false, false, false, 22), "html", null, true);
            yield "
            ";
            // line 23
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["invoice"]) || array_key_exists("invoice", $context) ? $context["invoice"] : (function () { throw new RuntimeError('Variable "invoice" does not exist.', 23, $this->source); })()), "appointment", [], "any", false, false, false, 23), "patient", [], "any", false, false, false, 23), "lastname", [], "any", false, false, false, 23), "html", null, true);
            yield "
        </p>
    ";
        }
        // line 26
        yield "</div>

";
        // line 28
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["invoice"]) || array_key_exists("invoice", $context) ? $context["invoice"] : (function () { throw new RuntimeError('Variable "invoice" does not exist.', 28, $this->source); })()), "appointment", [], "any", false, false, false, 28) && (Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["invoice"]) || array_key_exists("invoice", $context) ? $context["invoice"] : (function () { throw new RuntimeError('Variable "invoice" does not exist.', 28, $this->source); })()), "appointment", [], "any", false, false, false, 28), "treatments", [], "any", false, false, false, 28)) > 0))) {
            // line 29
            yield "    ";
            $context["treatment"] = Twig\Extension\CoreExtension::first($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["invoice"]) || array_key_exists("invoice", $context) ? $context["invoice"] : (function () { throw new RuntimeError('Variable "invoice" does not exist.', 29, $this->source); })()), "appointment", [], "any", false, false, false, 29), "treatments", [], "any", false, false, false, 29));
            // line 30
            yield "
    <div class=\"box\">
        <p>Acte : ";
            // line 32
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["treatment"]) || array_key_exists("treatment", $context) ? $context["treatment"] : (function () { throw new RuntimeError('Variable "treatment" does not exist.', 32, $this->source); })()), "name", [], "any", false, false, false, 32), "html", null, true);
            yield "</p>
        <p>Prix : ";
            // line 33
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["treatment"]) || array_key_exists("treatment", $context) ? $context["treatment"] : (function () { throw new RuntimeError('Variable "treatment" does not exist.', 33, $this->source); })()), "price", [], "any", false, false, false, 33), "html", null, true);
            yield " €</p>
    </div>
";
        }
        // line 36
        yield "
<p style=\"margin-top: 30px;\">Statut : Payée</p>

</body>
</html>";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "invoice/pdf.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  109 => 36,  103 => 33,  99 => 32,  95 => 30,  92 => 29,  90 => 28,  86 => 26,  80 => 23,  76 => 22,  69 => 19,  67 => 18,  62 => 16,  45 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <style>
        body { font-family: Arial; }
        h1 { text-align: center; }
        .box { margin-top: 20px; }
    </style>
</head>
<body>

<h1>Facture</h1>

<div class=\"box\">
    <p><strong>Facture #{{ invoice.id }}</strong></p>

    {% if invoice.appointment %}
        <p>Date : {{ invoice.appointment.date|date('d/m/Y') }}</p>
        <p>
            Patient :
            {{ invoice.appointment.patient.firstname }}
            {{ invoice.appointment.patient.lastname }}
        </p>
    {% endif %}
</div>

{% if invoice.appointment and invoice.appointment.treatments|length > 0 %}
    {% set treatment = invoice.appointment.treatments|first %}

    <div class=\"box\">
        <p>Acte : {{ treatment.name }}</p>
        <p>Prix : {{ treatment.price }} €</p>
    </div>
{% endif %}

<p style=\"margin-top: 30px;\">Statut : Payée</p>

</body>
</html>", "invoice/pdf.html.twig", "D:\\Logiciel\\XAMPP\\htdocs\\ProjectHealthNorth_API\\templates\\invoice\\pdf.html.twig");
    }
}
