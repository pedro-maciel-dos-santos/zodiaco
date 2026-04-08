<?php include('layouts/header.php'); ?>

<?php

// Recebe a data de nascimento do formulário
$data_nascimento = isset($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null;

// Valida se a data foi informada
if (!$data_nascimento) {
    header('Location: index.php');
    exit;
}

// Carrega o arquivo XML com os signos
$signos = simplexml_load_file("signos.xml");

// Converte a data de nascimento para timestamp
$dt_nasc = DateTime::createFromFormat('Y-m-d', $data_nascimento);

if (!$dt_nasc) {
    header('Location: index.php');
    exit;
}

$ano_nasc = $dt_nasc->format('Y');
$mes_nasc = (int) $dt_nasc->format('m');
$dia_nasc = (int) $dt_nasc->format('d');

/**
 * Converte uma data no formato "dd/mm" em um timestamp usando o ano fornecido.
 * Capricórnio tem início em dezembro — ajusta o ano se necessário.
 */
function converterData($data_str, $ano_base, $dia_nasc_mes, $dia_nasc_dia, $tipo = 'inicio')
{
    list($dia, $mes) = explode('/', $data_str);
    $dia = (int) $dia;
    $mes = (int) $mes;

    // Para Capricórnio: dataInicio é em dezembro (12), dataFim em janeiro (01)
    // Se o tipo é 'fim' e o mês do fim é menor que o mês do início, é virada de ano
    $ano = $ano_base;

    $dt = DateTime::createFromFormat('d/m/Y', "$dia/$mes/$ano");
    return $dt;
}

// Signo encontrado
$signo_encontrado = null;

foreach ($signos->signo as $signo) {
    $ini_str = (string) $signo->dataInicio;
    $fim_str = (string) $signo->dataFim;

    list($ini_dia, $ini_mes) = array_map('intval', explode('/', $ini_str));
    list($fim_dia, $fim_mes) = array_map('intval', explode('/', $fim_str));

    // Caso especial: Capricórnio cruza a virada do ano (dez → jan)
    if ($ini_mes > $fim_mes) {
        // Nasceu em dezembro
        $dt_ini = DateTime::createFromFormat('d/m/Y', "$ini_dia/$ini_mes/$ano_nasc");
        $dt_fim = DateTime::createFromFormat('d/m/Y', "$fim_dia/$fim_mes/" . ($ano_nasc + 1));

        if ($mes_nasc == 12) {
            $dt_fim2 = DateTime::createFromFormat('d/m/Y', "$fim_dia/$fim_mes/$ano_nasc");
            $dt_ini2 = DateTime::createFromFormat('d/m/Y', "$ini_dia/$ini_mes/" . ($ano_nasc - 1));
            if ($dt_nasc >= $dt_ini || $dt_nasc <= $dt_fim2) {
                $signo_encontrado = $signo;
                break;
            }
        } else {
            // Nasceu em janeiro
            $dt_ini2 = DateTime::createFromFormat('d/m/Y', "$ini_dia/$ini_mes/" . ($ano_nasc - 1));
            $dt_fim2 = DateTime::createFromFormat('d/m/Y', "$fim_dia/$fim_mes/$ano_nasc");
            if ($dt_nasc >= $dt_ini2 && $dt_nasc <= $dt_fim2) {
                $signo_encontrado = $signo;
                break;
            }
        }
    } else {
        // Caso normal: mesmo ano
        $dt_ini = DateTime::createFromFormat('d/m/Y', "$ini_dia/$ini_mes/$ano_nasc");
        $dt_fim = DateTime::createFromFormat('d/m/Y', "$fim_dia/$fim_mes/$ano_nasc");

        if ($dt_nasc >= $dt_ini && $dt_nasc <= $dt_fim) {
            $signo_encontrado = $signo;
            break;
        }
    }
}

// Formata a data de nascimento para exibição
$dt_formatada = $dt_nasc->format('d/m/Y');

// Mapeamento de elemento para ícone Bootstrap
$elemento_icone = [
    'Fogo'  => 'bi-fire',
    'Terra' => 'bi-tree',
    'Ar'    => 'bi-wind',
    'Água'  => 'bi-droplet'
];
?>

<main>
    <?php if ($signo_encontrado): ?>
    <section class="result-section">
        <div class="container py-5">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><i class="bi bi-house-door me-1"></i>Início</a>
                    </li>
                    <li class="breadcrumb-item active">Resultado</li>
                </ol>
            </nav>

            <div class="row justify-content-center">
                <div class="col-lg-9">

                    <!-- Main Card -->
                    <div class="result-card mb-4">
                        <div class="result-header text-center">
                            <div class="result-symbol"><?= (string)$signo_encontrado->simbolo ?></div>
                            <h1 class="result-sign-name"><?= htmlspecialchars((string)$signo_encontrado->signoNome) ?></h1>
                            <p class="result-dates">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?= htmlspecialchars((string)$signo_encontrado->dataInicio) ?> –
                                <?= htmlspecialchars((string)$signo_encontrado->dataFim) ?>
                            </p>
                            <span class="badge result-birth-badge">
                                <i class="bi bi-balloon-heart me-1"></i>
                                Nascido em <?= $dt_formatada ?>
                            </span>
                        </div>

                        <div class="result-body p-4">

                            <!-- Descrição -->
                            <div class="result-descricao mb-4">
                                <p class="lead"><?= htmlspecialchars((string)$signo_encontrado->descricao) ?></p>
                            </div>

                            <!-- Info Cards Row -->
                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-3">
                                    <div class="info-card text-center">
                                        <div class="info-icon">
                                            <i class="bi <?= $elemento_icone[(string)$signo_encontrado->elemento] ?? 'bi-stars' ?>"></i>
                                        </div>
                                        <div class="info-label">Elemento</div>
                                        <div class="info-value"><?= htmlspecialchars((string)$signo_encontrado->elemento) ?></div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-card text-center">
                                        <div class="info-icon">
                                            <i class="bi bi-globe2"></i>
                                        </div>
                                        <div class="info-label">Planeta</div>
                                        <div class="info-value"><?= htmlspecialchars((string)$signo_encontrado->planeta) ?></div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-card text-center">
                                        <div class="info-icon">
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <div class="info-label">Ponto Forte</div>
                                        <div class="info-value"><?= htmlspecialchars((string)$signo_encontrado->pontoForte) ?></div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-card text-center">
                                        <div class="info-icon">
                                            <i class="bi bi-exclamation-circle"></i>
                                        </div>
                                        <div class="info-label">Ponto Fraco</div>
                                        <div class="info-value"><?= htmlspecialchars((string)$signo_encontrado->pontoFraco) ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Características -->
                            <div class="caracteristicas-section mb-3">
                                <h5 class="section-label">
                                    <i class="bi bi-person-check me-2"></i>Características
                                </h5>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <?php
                                    $caract = explode(',', (string)$signo_encontrado->caracteristicas);
                                    foreach ($caract as $c): ?>
                                    <span class="badge caracteristica-badge"><?= htmlspecialchars(trim($c)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="text-center">
                        <a href="index.php" class="btn btn-voltar">
                            <i class="bi bi-arrow-left me-2"></i>Descobrir outro signo
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php else: ?>
    <!-- Erro: signo não encontrado -->
    <section class="error-section">
        <div class="container py-5 text-center">
            <div class="error-card mx-auto">
                <div class="error-icon mb-3">⚠️</div>
                <h2>Data inválida</h2>
                <p class="text-muted">Não foi possível determinar o seu signo com a data informada.</p>
                <a href="index.php" class="btn btn-zodiac mt-3">
                    <i class="bi bi-arrow-left me-2"></i>Tentar novamente
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<footer class="site-footer-bottom py-4 mt-auto">
    <div class="container text-center">
        <p class="mb-0 footer-text">
            <span class="me-2">✦</span> ZodiacSign — Explorando os Segredos do Universo
        </p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
