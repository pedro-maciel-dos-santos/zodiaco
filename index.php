<?php include('layouts/header.php'); ?>

    <!-- Hero Section -->
    <main>
        <section class="hero-section">
            <div class="stars-bg"></div>
            <div class="container">
                <div class="row justify-content-center align-items-center min-vh-hero">
                    <div class="col-lg-6 text-center hero-content">
                        <div class="zodiac-wheel mb-4">
                            <span class="wheel-symbol">☽</span>
                        </div>
                        <h1 class="hero-title">Descubra o Seu<br><span class="gradient-text">Signo Zodiacal</span></h1>
                        <p class="hero-subtitle">Insira sua data de nascimento e explore os segredos que os astros revelam sobre a sua personalidade.</p>

                        <!-- Form Card -->
                        <div class="form-card mx-auto mt-4">
                            <form id="signo-form" method="POST" action="show_zodiac_sign.php" novalidate>
                                <label for="data_nascimento" class="form-label">
                                    <i class="bi bi-calendar3 me-2"></i>Data de Nascimento
                                </label>
                                <div class="input-group mb-3">
                                    <input
                                        type="date"
                                        class="form-control form-control-lg date-input"
                                        id="data_nascimento"
                                        name="data_nascimento"
                                        max="<?= date('Y-m-d') ?>"
                                        min="1900-01-01"
                                        required
                                    >
                                </div>
                                <div class="invalid-feedback d-none" id="date-error">
                                    Por favor, insira uma data de nascimento válida.
                                </div>
                                <button type="submit" class="btn btn-zodiac w-100">
                                    <i class="bi bi-search me-2"></i>Descobrir Meu Signo
                                    <span class="btn-arrow">→</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Signs Preview Section -->
        <section class="signs-section py-5" id="sobre">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title">Os 12 Signos do Zodíaco</h2>
                    <p class="section-subtitle">Cada signo carrega características únicas moldadas pelos astros</p>
                </div>
                <div class="row g-3 justify-content-center">
                    <?php
                    $signos_preview = [
                        ['♈', 'Áries', '21/03 – 20/04'],
                        ['♉', 'Touro', '21/04 – 20/05'],
                        ['♊', 'Gêmeos', '21/05 – 20/06'],
                        ['♋', 'Câncer', '21/06 – 22/07'],
                        ['♌', 'Leão', '23/07 – 22/08'],
                        ['♍', 'Virgem', '23/08 – 22/09'],
                        ['♎', 'Libra', '23/09 – 22/10'],
                        ['♏', 'Escorpião', '23/10 – 21/11'],
                        ['♐', 'Sagitário', '22/11 – 21/12'],
                        ['♑', 'Capricórnio', '22/12 – 20/01'],
                        ['♒', 'Aquário', '21/01 – 18/02'],
                        ['♓', 'Peixes', '19/02 – 20/03'],
                    ];
                    foreach ($signos_preview as $s): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="sign-card text-center">
                            <div class="sign-symbol"><?= $s[0] ?></div>
                            <div class="sign-name"><?= $s[1] ?></div>
                            <div class="sign-dates"><?= $s[2] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer-bottom py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0 footer-text">
                <span class="me-2">✦</span> Zodiaco — Explorando os Segredos do Universo
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
