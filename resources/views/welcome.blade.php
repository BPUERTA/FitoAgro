<!doctype html>
<html lang="es-AR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  @php
    $siteTitle = setting('site_title', 'FitoAgro Gestión');
    $siteDescription = setting('site_description', 'Plataforma de gestión para asesores: empresas, clientes y explotaciones agropecuarias.');
    $brandPrimary = setting('brand_primary', '#16a34a');
    $brandSecondary = setting('brand_secondary', '#0f7a37');
    $homeImages = [
      setting('home_image_1'),
      setting('home_image_2'),
      setting('home_image_3'),
    ];
    $slides = array_values(array_filter($homeImages));
    if (count($slides) === 0) {
      $slides = [
        'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=1920&q=85',
        'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1920&q=85',
        'https://images.unsplash.com/photo-1470509037663-253afd7f0f51?w=1920&q=85',
      ];
    } else {
      $slides = array_map(fn($path) => asset('storage/' . $path), $slides);
    }
  @endphp
  <title>{{ $siteTitle }}</title>
  <meta name="description" content="{{ $siteDescription }}" />
  @if(setting('favicon'))
    <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
  @endif

  <style>
    :root{
      --bg:#ffffff;
      --text:#101828;
      --muted:rgba(16,24,40,.64);
      --stroke:rgba(16,24,40,.12);

      --green:{{ $brandPrimary }};
      --greenDark:{{ $brandSecondary }};
      --greenSoft:rgba(22,163,74,.10);

      --radius:18px;
      --shadow:0 16px 50px rgba(16,24,40,.08);
    }

    *{ box-sizing:border-box; }

    body{
      margin:0;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, Helvetica, sans-serif;
      color:var(--text);
      background: #000;
      min-height:100vh;
      overflow-x: hidden;
    }

    /* Hero Section - Fullscreen with background image */
    .hero-section {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
      overflow: hidden;
    }

    /* Background Image Slideshow */
    .hero-bg {
      position: absolute;
      inset: 0;
      z-index: 0;
    }

    .hero-bg::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(
        135deg,
        rgba(0, 0, 0, 0.5) 0%,
        rgba(22, 163, 74, 0.3) 50%,
        rgba(0, 0, 0, 0.6) 100%
      );
      z-index: 1;
    }

    .bg-image {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      animation: kenburns 25s ease-in-out infinite;
      opacity: 0;
      transition: opacity 2s ease-in-out;
    }

    .bg-image.active {
      opacity: 1;
    }

    .bg-image { animation-duration: {{ max(count($slides), 1) * 8 }}s; }

    @keyframes kenburns {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }

    /* Main content wrapper */
    .wrap{
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 1400px;
      display: grid;
      grid-template-columns: 1.3fr 0.7fr;
      gap: 30px;
      padding: 0 40px;
    }
    @media (max-width: 1024px){
      .wrap{ 
        grid-template-columns: 1fr; 
        max-width: 800px;
        padding: 0 20px;
      }
    }

    /* Hero card */
    .hero{
      padding: 50px 45px;
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: var(--radius);
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
      position: relative;
      overflow: hidden;
    }
    .hero::before{
      content:"";
      position:absolute;
      inset:-120px -120px auto auto;
      width:340px;
      height:340px;
      background: radial-gradient(circle, rgba(22,163,74,.15), transparent 60%);
      transform: rotate(15deg);
      pointer-events:none;
    }

    .badge{
      display:inline-flex;
      gap:10px;
      align-items:center;
      font-size:13px;
      padding:10px 16px;
      border:1px solid rgba(22,163,74,.25);
      border-radius:999px;
      color: var(--greenDark);
      background: var(--greenSoft);
      font-weight: 500;
    }

    h1{
      margin:20px 0 16px;
      font-size:56px;
      line-height:1.1;
      letter-spacing:-1px;
      font-weight: 700;
    }
    @media (max-width: 768px){
      h1{ font-size:42px; }
    }
    @media (max-width: 480px){
      h1{ font-size:36px; }
    }

    p{
      margin:0;
      color:var(--muted);
      font-size:18px;
      line-height:1.7;
      max-width: 62ch;
    }

    .cta{
      margin-top:30px;
      display:flex;
      gap:14px;
      flex-wrap:wrap;
    }

    .btn{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:14px 24px;
      border-radius: 12px;
      border:1px solid var(--stroke);
      text-decoration:none;
      font-weight:600;
      font-size: 15px;
      min-width:180px;
      cursor:pointer;
      transition: all .2s ease;
      user-select:none;
    }
    .btn:active{ transform: translateY(1px); }

    .btnPrimary{
      background: var(--green);
      color:#fff;
      border-color: transparent;
      box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
    }
    .btnPrimary:hover{ 
      background: var(--greenDark);
      box-shadow: 0 6px 20px rgba(22, 163, 74, 0.4);
      transform: translateY(-2px);
    }

    .btnGhost{
      background:#fff;
      color: var(--greenDark);
      border-color: rgba(22,163,74,.35);
    }
    .btnGhost:hover{ 
      background: rgba(22,163,74,.08);
      border-color: var(--green);
    }

    .grid{
      margin-top:30px;
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap:14px;
    }
    @media (max-width: 1024px){
      .grid{ grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 640px){
      .grid{ grid-template-columns: 1fr; }
    }

    .mini{
      padding:18px 18px;
      border:1px solid var(--stroke);
      border-radius: 14px;
      background: rgba(255,255,255,.95);
      position:relative;
      overflow:hidden;
      transition: all 0.3s ease;
    }
    .mini:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(22, 163, 74, 0.15);
    }
    .mini::before{
      content:"";
      position:absolute;
      left:0; top:0; bottom:0;
      width:4px;
      background: var(--green);
      opacity:.8;
    }
    .mini b{
      display:block;
      margin:0 0 8px 10px;
      font-size:15px;
      font-weight: 600;
    }
    .mini span{
      display:block;
      margin-left:10px;
      color:var(--muted);
      font-size:14px;
      line-height:1.5;
    }

    .foot{
      margin-top:24px;
      color: rgba(16,24,40,.45);
      font-size:13px;
    }

    /* Side card */
    .side{
      padding:36px 32px;
      border:1px solid rgba(255, 255, 255, 0.15);
      border-radius: var(--radius);
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    }
    .side h2{
      margin:0 0 16px;
      font-size:22px;
      font-weight: 600;
    }
    .list{
      margin:0;
      padding-left:20px;
      color:var(--muted);
      line-height:1.8;
      font-size:15px;
    }

    .list li {
      margin-bottom: 8px;
    }

    .note{
      margin-top:18px;
      padding:16px 16px;
      border:1px dashed rgba(22,163,74,.35);
      border-radius: 14px;
      color: rgba(16,24,40,.65);
      font-size:13px;
      line-height:1.6;
      background: rgba(22,163,74,.08);
    }

    /* Call to Action Box */
    .cta-box {
      margin-bottom: 24px;
      padding: 24px 20px;
      border-radius: 16px;
      background: linear-gradient(135deg, var(--greenSoft) 0%, rgba(22,163,74,.05) 100%);
      border: 2px solid rgba(22,163,74,.25);
      text-align: center;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .cta-box:hover {
      border-color: var(--green);
      box-shadow: 0 8px 24px rgba(22, 163, 74, 0.2);
      transform: translateY(-2px);
    }

    .cta-box::before {
      content: "";
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(22,163,74,.08) 0%, transparent 70%);
      animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.5; }
      50% { transform: scale(1.1); opacity: 0.8; }
    }

    .cta-icon {
      font-size: 32px;
      margin-bottom: 8px;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-8px); }
    }

    @keyframes slideInFromRight {
      from {
        opacity: 0;
        transform: translateX(100%);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideOutToLeft {
      from {
        opacity: 1;
        transform: translateX(0);
      }
      to {
        opacity: 0;
        transform: translateX(-100%);
      }
    }

    .plan-slide {
      transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .cta-box h3 {
      margin: 8px 0;
      font-size: 20px;
      font-weight: 700;
      color: var(--text);
      position: relative;
      z-index: 1;
    }

    .cta-box p {
      margin: 8px 0 0;
      font-size: 14px;
      color: var(--muted);
      line-height: 1.5;
      position: relative;
      z-index: 1;
      max-width: 100%;
    }

    .cta-box .btn {
      position: relative;
      z-index: 1;
      font-size: 16px;
      font-weight: 600;
      min-width: auto;
    }

  </style>
</head>

<body>
  <div class="hero-section">
    <!-- Background Image Slideshow -->
    <div class="hero-bg">
      @foreach($slides as $index => $url)
        <div class="bg-image {{ $index === 0 ? 'active' : '' }}" style="background-image: url('{{ $url }}'); animation-delay: {{ $index * 8 }}s;"></div>
      @endforeach
    </div>

    <!-- Main Content -->
    <main class="wrap">
    <section class="hero">
      <div class="badge">
        @if(setting('logo'))
          <img src="{{ asset('storage/' . setting('logo')) }}" alt="Logo" style="height:60px;width:auto;margin-right:12px;" />
        @endif
        {{ setting('site_tagline', 'Plataforma para asesores y productores agropecuarios') }}
      </div>

      <h1>{{ setting('home_headline', 'Gestión simple para asesorar mejor.') }}</h1>
      <p>
        {{ setting('home_subheadline', 'Administrá tu organización, tus usuarios, tu cartera de clientes y sus explotaciones agropecuarias.') }}
      </p>

      <div class="cta">
        <a class="btn btnPrimary" href="{{ setting('cta_primary_url', '/login') }}">
          {{ setting('cta_primary_text', 'Iniciar sesión') }}
        </a>
        <a class="btn btnGhost" href="{{ setting('cta_secondary_url', '/register') }}">
          {{ setting('cta_secondary_text', 'Crear cuenta') }}
        </a>
      </div>

      <div class="grid">
        <div class="mini">
          <b>Multi-Usuario</b>
          <span>Gestiona tu organización vos solo/a o en grupo de trabajo.</span>
        </div>
        <div class="mini">
          <b>Cartera de clientes</b>
          <span>Administra tus clientes y sus explotaciones agropecuarias.</span>
        </div>
        <div class="mini">
          <b>Ordenes de trabajo</b>
          <span>Crea ordenes de fumigación, fertilización, siembra entre otros.</span>
        </div>
      </div>

      <div class="foot">© <span id="y"></span> FitoAgro Gestión — fitoagrogestion.com</div>
    </section>

    <aside class="side">
      <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 16px; color: #111827;">Planes</h2>      
      
      <?php if($publishedPlans->count() > 0): ?>
        <div class="plans-slider" style="position: relative; min-height: 500px;">
          <?php $__currentLoopData = $publishedPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="plan-slide" data-index="<?php echo e($index); ?>" style="display: <?php echo e($index === 0 ? 'block' : 'none'); ?>;">
              <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: all 0.2s;">
                
                <?php if($plan->trial_days > 0): ?>
                  <span style="background: #3b82f6; color: white; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-block; margin-bottom: 12px;">
                    <?php echo e($plan->trial_days); ?> días gratis
                  </span>
                <?php endif; ?>
                
                <h3 style="font-size: 22px; font-weight: 700; margin: 0 0 8px 0; color: #111827;"><?php echo e($plan->name); ?></h3>
                
                <?php if($plan->description): ?>
                  <p style="font-size: 13px; color: #6b7280; margin-bottom: 16px; line-height: 1.5;"><?php echo e($plan->description); ?></p>
                <?php endif; ?>
                
                <div style="margin: 20px 0;">
                  <?php if($plan->monthly_price): ?>
                    <div style="display: flex; align-items: baseline; gap: 4px;">
                      <span style="font-size: 36px; font-weight: 700; color: #111827;"><?php echo e(number_format($plan->monthly_price, 2)); ?></span>
                      <span style="font-size: 14px; font-weight: 600; color: #6b7280;"><?php echo e($plan->currency); ?>/mes</span>
                    </div>
                  <?php endif; ?>
                  
                  <?php if($plan->yearly_price && $plan->monthly_price && $plan->annual_discount > 0): ?>
                    <div style="margin-top: 12px; padding: 10px; background: #f0fdf4; border-radius: 8px;">
                      <div style="font-size: 13px; color: #059669; font-weight: 600;">
                        <?php echo e(number_format($plan->yearly_price, 2)); ?> <?php echo e($plan->currency); ?>/año
                      </div>
                      <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">
                        Ahorra <?php echo e(number_format($plan->annual_discount, 0)); ?>%
                      </div>
                      <?php if($plan->max_users): ?>
                        <div style="font-size: 11px; color: #6b7280; margin-top: 4px;">
                          <?php echo e(number_format($plan->yearly_price / $plan->max_users, 2)); ?> <?php echo e($plan->currency); ?>/año por usuario
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>
                
                <div style="padding-top: 16px; border-top: 1px solid #e5e7eb;">
                  <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; color: #374151;">
                    <li style="padding: 6px 0; display: flex; align-items: center;">
                      <svg style="width: 16px; height: 16px; color: #10b981; margin-right: 8px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <span><?php echo e($plan->max_users ? $plan->max_users . ($plan->max_users == 1 ? ' usuario' : ' usuarios') : 'Usuarios ilimitados'); ?></span>
                    </li>
                    <li style="padding: 6px 0; display: flex; align-items: center;">
                      <svg style="width: 16px; height: 16px; color: #10b981; margin-right: 8px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <span><?php echo e($plan->max_work_orders ? $plan->max_work_orders . ($plan->max_work_orders == 1 ? ' orden' : ' órdenes') : 'Órdenes ilimitadas'); ?></span>
                    </li>
                    <li style="padding: 6px 0; display: flex; align-items: center;">
                      <svg style="width: 16px; height: 16px; color: #10b981; margin-right: 8px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <span><?php echo e($plan->max_farms ? $plan->max_farms . ($plan->max_farms == 1 ? ' explotación' : ' explotaciones') : 'Explotaciones ilimitadas'); ?></span>
                    </li>
                    <?php if($plan->trial_days > 0): ?>
                      <li style="padding: 6px 0; display: flex; align-items: center;">
                        <svg style="width: 16px; height: 16px; color: #10b981; margin-right: 8px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Pruébalo <?php echo e($plan->trial_days); ?> <?php echo e($plan->trial_days == 1 ? 'día' : 'días'); ?> gratis antes del primer pago</span>
                      </li>
                    <?php endif; ?>
                  </ul>
                </div>
                
                <a href="/register?plan_id=<?php echo e($plan->id); ?>" style="display: block; text-align: center; margin-top: 20px; padding: 12px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                  <?php if($plan->trial_days > 0): ?>
                    Probar gratis
                  <?php else: ?>
                    Comenzar
                  <?php endif; ?>
                </a>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          
          <?php if($publishedPlans->count() > 1): ?>
            <div style="display: flex; justify-content: center; gap: 6px; margin-top: 16px;">
              <?php $__currentLoopData = $publishedPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button class="slider-dot" data-slide="<?php echo e($index); ?>" 
                        style="width: 6px; height: 6px; border-radius: 50%; border: none; background: <?php echo e($index === 0 ? '#10b981' : '#d1d5db'); ?>; cursor: pointer; padding: 0; transition: all 0.2s;">
                </button>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="cta-box">
          <div class="cta-icon">✨</div>
          <h3>¿Listo para comenzar?</h3>
          <p>Únete a FitoAgro Gestión y simplifica el manejo de tu negocio agropecuario.</p>
          <a href="/register" class="btn btnPrimary" style="width: 100%; margin-top: 12px;">
            Registrate ahora
          </a>
        </div>
      <?php endif; ?>

    </aside>
    </main>

  </div>

  <script>
    // Update year
    document.getElementById("y").textContent = new Date().getFullYear();

    // Background image slideshow
    const images = document.querySelectorAll('.bg-image');
    let currentIndex = 0;

    function rotateImages() {
      images[currentIndex].classList.remove('active');
      currentIndex = (currentIndex + 1) % images.length;
      images[currentIndex].classList.add('active');
    }

    setInterval(rotateImages, 8000);

    // Plans slider
    const planSlides = document.querySelectorAll('.plan-slide');
    const sliderDots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prevPlan');
    const nextBtn = document.getElementById('nextPlan');
    let currentPlanIndex = 0;
    let planSliderInterval;

    function showPlan(index) {
      // Ocultar todos los slides primero
      planSlides.forEach((slide) => {
        slide.style.display = 'none';
      });
      
      // Mostrar solo el slide seleccionado
      planSlides[index].style.display = 'block';
      
      // Actualizar los dots
      sliderDots.forEach((dot, i) => {
        dot.style.background = i === index ? '#10b981' : '#d1d5db';
      });
      
      currentPlanIndex = index;
    }

    function nextPlan() {
      currentPlanIndex = (currentPlanIndex + 1) % planSlides.length;
      showPlan(currentPlanIndex);
    }

    function prevPlan() {
      currentPlanIndex = (currentPlanIndex - 1 + planSlides.length) % planSlides.length;
      showPlan(currentPlanIndex);
    }

    function startPlanSlider() {
      if (planSlides.length > 1) {
        planSliderInterval = setInterval(nextPlan, 5000);
      }
    }

    function resetPlanSlider() {
      clearInterval(planSliderInterval);
      startPlanSlider();
    }

    if (planSlides.length > 1) {
      if (prevBtn) {
        prevBtn.addEventListener('click', () => {
          prevPlan();
          resetPlanSlider();
        });
      }
      
      if (nextBtn) {
        nextBtn.addEventListener('click', () => {
          nextPlan();
          resetPlanSlider();
        });
      }
      
      sliderDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          showPlan(index);
          resetPlanSlider();
        });
      });
      
      startPlanSlider();
    }
  </script>
</body>
</html>
