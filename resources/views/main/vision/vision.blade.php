@extends('main.layouts.app')

@section('title', 'Vision — AL-FARAN School of Excellence')
@section('body_class', 'page--vision')

@section('content')
<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --radius:18px;
  }
  @media (prefers-color-scheme: dark){
    :root{
      --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830;
      --stroke:rgba(255,255,255,.12);
    }
  }

  body.page--vision .dashboard-main{
    padding-left: 0 !important;
    margin-left: 0 !important;
  }
  body.page--vision .page-container,
  body.page--vision .container,
  body.page--vision .content{
    padding-left: 0 !important;
    margin-left: 0 !important;
  }

  .page{ background:var(--bg); color:var(--ink); min-height:100dvh; }
  .hero{
    background:linear-gradient(135deg,var(--brand1),var(--brand2));
    padding:80px 14px;
    text-align:center;
    color:#fff;
  }
  .hero h1{ font-size: clamp(32px,6vw,64px); font-weight:900; margin-bottom:10px; }
  .hero p{ max-width:680px; margin:0 auto; font-size:18px; line-height:1.6; opacity:.95; }

  .wrap{ max-width:1100px; margin:0 auto; padding:40px 18px; }

  h2{ font-size:clamp(24px,4vw,40px); font-weight:800; margin:28px 0 16px;
      background:linear-gradient(90deg,var(--brand1),var(--brand2));
      -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
  p{ font-size:17px; line-height:1.8; margin-bottom:18px; color:var(--ink); }

  ul{ margin:16px 0 24px 20px; list-style:disc; }
  ul li{ margin-bottom:8px; font-size:17px; line-height:1.7; }

  .card{
    background:var(--card);
    padding:28px;
    border-radius:var(--radius);
    border:1px solid var(--stroke);
    margin-bottom:36px;
    box-shadow:0 12px 28px rgba(2,6,23,.06);
  }
</style>

<div class="page">

  <!-- Hero Section -->
  <section class="hero">
    <h1>Our Vision & Mission</h1>
    <p>Shaping the future of education with science, technology, AI, and values for every child.</p>
  </section>

  <!-- Content -->
  <div class="wrap">

    <div class="card">
      <h2>Vision Statement</h2>
      <p>Our vision is to establish a center of excellence in education within our village that redefines the meaning of learning by combining the power of science, technology, and human values. We aspire to create a model school where quality education is not a privilege but a right—accessible, affordable, and impactful for every child, regardless of their socio-economic background.</p>

      <p>We envision a future where our students are not confined to memorizing facts but are guided toward understanding concepts deeply, questioning ideas critically, and applying knowledge meaningfully in their daily lives. By moving beyond the culture of cramming, we aim to nurture curiosity, creativity, and a spirit of lifelong learning in every learner.</p>

      <p>Artificial Intelligence will play a central role in this journey, serving not as a replacement for teachers but as a powerful companion that enhances teaching and learning. Through AI-driven tools and personalized learning systems, we will ensure that every child receives individual attention according to their unique pace and style of learning. This integration of technology will allow us to maintain high standards of education while keeping costs low, ensuring that even in a rural setting, our students are not left behind in the age of digital transformation.</p>

      <p>Our school will stand as a living example of innovation in education—where science is not only taught as a subject but experienced as a way of thinking. Students will engage in hands-on experiments, problem-solving activities, and real-life applications that connect their classroom learning to the world around them. These practices will not only strengthen their academic foundation but also prepare them to face future challenges with confidence, adaptability, and responsibility.</p>

      <p>We are committed to building an environment of respect, inclusivity, and collaboration where teachers act as mentors, parents as partners, and students as active participants in their own learning. By fostering a culture of discipline, empathy, and integrity, we seek to shape individuals who are not only knowledgeable but also socially responsible and morally grounded.</p>

      <p>Ultimately, our vision is to see our school evolve into a transformative learning hub that ignites the potential of every student, equipping them with the scientific knowledge, technological fluency, and ethical values needed to become innovators, leaders, and change-makers in society. In doing so, we aim to contribute not only to the progress of our community but also to the advancement of our nation and the world.</p>
    </div>

    <div class="card">
      <h2>Mission Statement</h2>
      <p>Our mission is to provide high-quality, affordable, and concept-driven education that empowers every child in our village to grow into a confident, skilled, and responsible citizen of the modern world. We are committed to breaking the cycle of rote learning and instead nurturing students’ natural curiosity, creativity, and problem-solving abilities through innovative teaching practices and relevant scientific activities.</p>

      <p><strong>To achieve this mission, we will:</strong></p>
      <ul>
        <li><strong>Promote Conceptual Learning:</strong> Replace cramming with understanding by encouraging inquiry, experimentation, and critical thinking in all subjects, with a special focus on science and technology.</li>
        <li><strong>Integrate Artificial Intelligence:</strong> Use AI-based tools to personalize education, track progress, and provide students with adaptive support—ensuring that no learner is left behind, regardless of pace or background.</li>
        <li><strong>Ensure Affordability and Accessibility:</strong> Deliver world-class educational experiences within a low-budget framework so that families in rural communities can access quality education without financial burden.</li>
        <li><strong>Encourage Hands-On Activities:</strong> Organize regular experiments, projects, and real-life applications to help students connect theory with practice and see the relevance of their learning in everyday life.</li>
        <li><strong>Empower Teachers:</strong> Provide continuous training and exposure to modern pedagogies and AI tools so that our educators remain facilitators of meaningful learning rather than transmitters of information.</li>
        <li><strong>Build Strong Values:</strong> Alongside scientific knowledge, instill values of integrity, empathy, discipline, and social responsibility, so that students become not only successful professionals but also good human beings.</li>
        <li><strong>Engage the Community:</strong> Involve parents, local experts, and the broader community as partners in education, fostering a collaborative environment that strengthens both the school and the society it serves.</li>
        <li><strong>Prepare for the Future:</strong> Equip students with 21st-century skills—including digital literacy, communication, and teamwork—so they are ready to thrive in a rapidly changing world.</li>
      </ul>

      <p>Through these commitments, our mission is to create a learning ecosystem where every student feels inspired, supported, and challenged to reach their fullest potential. We seek to transform our school into a beacon of hope and progress in the village—a place where education is not about memorizing books, but about unlocking the power of knowledge, technology, and values to shape a brighter future for individuals and the community alike.</p>
    </div>

  </div>
</div>
@endsection
