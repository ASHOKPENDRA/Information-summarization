<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Deep Learning — Summaries (Goodfellow, Bengio & Courville)</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  
  <style>
    :root{
      --accent:#007acc;
      --muted:#666;
      --card:#fff;
      --bg:#f6f8fb;
      --sidebar:#e9eef6;
      --radius:12px;
      --maxw:1200px;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      background:var(--bg);
      color:#222;
      line-height:1.6;
      -webkit-font-smoothing:antialiased;
    }
    header{
      background:linear-gradient(90deg,var(--accent),#0060b8);
      color:white;
      padding:22px 18px;
      text-align:left;
      box-shadow:0 2px 8px rgba(2,20,50,0.08);
    }
    header .title{
      font-size:20px;
      margin:0 0 6px 0;
      display:flex;
      gap:12px;
      align-items:center;
    }
    header .meta{font-size:13px; opacity:0.95}
    .wrap{
      max-width:var(--maxw);
      margin:22px auto;
      display:grid;
      grid-template-columns: 300px 1fr;
      gap:20px;
      padding:0 16px;
    }
    nav.sidebar{
      background:var(--sidebar);
      padding:18px;
      border-radius:var(--radius);
      height:calc(100vh - 120px);
      overflow:auto;
      position:sticky;
      top:18px;
      box-shadow:0 2px 6px rgba(5,20,50,0.04);
    }
    nav.sidebar h2{margin:0 0 8px 0; font-size:16px; color:var(--accent)}
    nav.sidebar .mini{font-size:13px; color:var(--muted); margin-bottom:12px}
    nav ul{list-style:none; padding:0; margin:0}
    nav li{margin:6px 0}
    nav a{
      color:var(--accent);
      text-decoration:none;
      font-size:14px;
    }
    nav a.small{font-size:13px; color:#234e7a}
    main.content{
      padding:20px;
      min-height:60vh;
    }
    section.card{
      background:var(--card);
      border-radius:12px;
      padding:18px;
      margin-bottom:18px;
      box-shadow:0 1px 3px rgba(2,20,50,0.04);
    }
    h2.unit{color:var(--accent); margin:0 0 14px 0}
    h3.topic{margin:12px 0 6px 0; color:#1f4f7a}
    p{margin:0 0 10px 0; color:#222}
    .ref{font-size:13px; color:var(--muted); margin-bottom:8px}
    .example{background:#f3f8ff; border-left:4px solid var(--accent); padding:10px; border-radius:6px; margin:8px 0}
    footer{max-width:var(--maxw); margin:20px auto 60px; padding:0 16px; color:var(--muted); font-size:13px}
    @media(max-width:900px){
      .wrap{grid-template-columns:1fr; padding-bottom:60px}
      nav.sidebar{position:relative;height:auto; margin-bottom:12px}
    }
  </style>
</head>
<body>
  <header>
    <div class="title">📖 Deep Learning — Summaries</div>
    <div class="meta">Author: Ian Goodfellow, Yoshua Bengio and Aaron Courville — MIT Press</div>
  </header>

  <div class="wrap">
    <nav class="sidebar" aria-label="table of contents">
      <h2>Contents</h2>
      <div class="mini">Click a unit or subtopic to jump</div>
      <ul>
        <li><a href="#unit1">UNIT 1: Machine Learning Basics</a></li>
        <ul>
          <li><a class="small" href="#u1-learning">Learning Algorithms & Capacity</a></li>
          <li><a class="small" href="#u1-hyper">Hyperparameters & Validation</a></li>
          <li><a class="small" href="#u1-biasvar">Estimators, Bias & Variance</a></li>
          <li><a class="small" href="#u1-mle">Maximum Likelihood Estimation</a></li>
          <li><a class="small" href="#u1-bayes">Bayesian Statistics</a></li>
          <li><a class="small" href="#u1-sgd">SGD & Building Algorithms</a></li>
          <li><a class="small" href="#u1-feed">Deep Feedforward Networks</a></li>
        </ul>

        <li><a href="#unit2">UNIT 2: Regularization for Deep Learning</a></li>
        <ul>
          <li><a class="small" href="#u2-norms">Parameter Norm Penalties</a></li>
          <li><a class="small" href="#u2-constrained">Norms as Constrained Opt</a></li>
          <li><a class="small" href="#u2-aug">Dataset Augmentation & Noise</a></li>
          <li><a class="small" href="#u2-ssl-mtl">Semi-Supervised & Multi-Task</a></li>
          <li><a class="small" href="#u2-early">Early Stopping & Tying</a></li>
          <li><a class="small" href="#u2-ensembles">Ensembles & Dropout</a></li>
          <li><a class="small" href="#u2-adv">Adversarial & Tangent Methods</a></li>
          <li><a class="small" href="#u2-opt">Optimization & Init</a></li>
        </ul>

        <li><a href="#unit3">UNIT 3: Convolutional Networks</a></li>
        <ul>
          <li><a class="small" href="#u3-conv">Convolution Operation</a></li>
          <li><a class="small" href="#u3-pool">Pooling & Priors</a></li>
          <li><a class="small" href="#u3-variants">Variants & Efficient Algos</a></li>
          <li><a class="small" href="#u3-structured">Structured Outputs & Data Types</a></li>
        </ul>

        <li><a href="#unit4">UNIT 4: Recurrent & Recursive Nets</a></li>
        <ul>
          <li><a class="small" href="#u4-unfold">Unfolding & RNN basics</a></li>
          <li><a class="small" href="#u4-encoder">Encoder-Decoder & Deep RNNs</a></li>
          <li><a class="small" href="#u4-long">Long-Term Dependencies & LSTM</a></li>
          <li><a class="small" href="#u4-memory">Echo State & Explicit Memory</a></li>
        </ul>

        <li><a href="#unit5">UNIT 5: Practical Methodology</a></li>
        <ul>
          <li><a class="small" href="#u5-metrics">Performance Metrics</a></li>
          <li><a class="small" href="#u5-baseline">Baseline Models</a></li>
          <li><a class="small" href="#u5-data">Gathering More Data</a></li>
          <li><a class="small" href="#u5-hp">Hyperparameters & Debugging</a></li>
          <li><a class="small" href="#u5-case">Case Study: Multi-Digit Recognition</a></li>
        </ul>
      </ul>
    </nav>

    <main class="content" role="main">
      <!-- UNIT 1 -->
      <section id="unit1" class="card">
        <h2 class="unit">UNIT 1: MACHINE LEARNING BASICS</h2>

        <h3 id="u1-learning" class="topic">Learning Algorithms, Capacity, Overfitting and Underfitting</h3>
        <div class="ref">Chapter 5 — Machine Learning Basics (Goodfellow et al.)</div>
        <p>Machine learning algorithms improve by observing data (experience). The concept of capacity describes how flexible a model is — its ability to represent many different functions. Models with too little capacity (e.g., a linear model for a nonlinear problem) underfit and fail to capture training structure; models with excessive capacity (e.g., huge deep nets) can fit training data perfectly but then overfit, showing poor generalization to new examples. Practical deep learning strikes a balance using model selection, regularization, and validation to achieve good generalization without losing expressivity.</p>

        <h3 id="u1-hyper" class="topic">Hyperparameters and Validation Sets</h3>
        <div class="ref">Chapter 5 — Machine Learning Basics</div>
        <p>Parameters (weights, biases) are learned from data; hyperparameters (learning rate, architecture depth, regularization strength) are set prior to training. The validation set is a crucial tool for tuning hyperparameters: you evaluate candidate configurations on held-out validation data and select the best-performing one. Relying solely on training error is misleading — the validation set provides an unbiased check during development, and a final test set provides the true generalization estimate.</p>

        <h3 id="u1-biasvar" class="topic">Estimators, Bias and Variance</h3>
        <div class="ref">Chapter 5.2 — Estimators, Bias and Variance</div>
        <p>An estimator maps training data to predictions or parameter estimates. Its error decomposes into bias (error from wrong assumptions or an overly simple model) and variance (sensitivity to different training samples). The bias–variance tradeoff is a guiding principle: increasing model complexity often reduces bias but increases variance. Techniques like regularization, ensembling, or more data help shift that tradeoff for better generalization.</p>

        <h3 id="u1-mle" class="topic">Maximum Likelihood Estimation (MLE)</h3>
        <div class="ref">Chapter 5.5 — Maximum Likelihood Estimation</div>
        <p>MLE chooses parameters that maximize the likelihood of the observed data under a model. In practice, many deep learning losses are negative log-likelihoods — for example, cross-entropy loss for classification is the negative log-likelihood of the correct class under a softmax. MLE provides a principled framework that connects probabilistic modeling and loss design.</p>

        <h3 id="u1-bayes" class="topic">Bayesian Statistics</h3>
        <div class="ref">Chapter 5.6 — Bayesian Statistics</div>
        <p>Bayesian methods treat unknown parameters as random variables with prior distributions. Observed data updates the prior to a posterior using Bayes' rule. This framework explicitly represents uncertainty about parameters and predictions, which is valuable for robust decision-making. Exact Bayesian inference is often intractable in deep models, so practical approaches use approximations (e.g., variational inference, Monte Carlo methods) in Bayesian deep learning research.</p>

        <h3 id="u1-super" class="topic">Supervised Learning Algorithms</h3>
        <div class="ref">Chapter 5.7 — Supervised Algorithms</div>
        <p>Supervised learning uses labeled examples to learn mappings from inputs to outputs. Classical algorithms include linear regression, logistic regression, decision trees, and neural networks. The choice of model depends on the task, the amount of data, and the desired bias–variance tradeoff. Deep networks are powerful supervised learners when sufficient labeled data is available.</p>

        <h3 id="u1-unsuper" class="topic">Unsupervised Learning Algorithms</h3>
        <div class="ref">Chapter 5.8 — Unsupervised Algorithms</div>
        <p>Unsupervised learning finds structure in unlabeled data: clustering (k-means), dimensionality reduction (PCA), density estimation, and representation learning (autoencoders). These methods are key for pretraining, exploratory data analysis, and learning features when labels are scarce.</p>

        <h3 id="u1-sgd" class="topic">Stochastic Gradient Descent (SGD)</h3>
        <div class="ref">Chapter 8 — Optimization for Training Deep Models</div>
        <p>SGD updates model parameters by computing approximate gradients on small batches of data. Its noise helps exploration of parameter space and often improves generalization. Practitioners commonly use variants such as momentum, AdaGrad, RMSProp, and Adam that accelerate convergence, adapt learning rates, or stabilize training across layers.</p>

        <h3 id="u1-build" class="topic">Building a Machine Learning Algorithm</h3>
        <div class="ref">Chapter 5 & 8 — Practical steps</div>
        <p>Typical workflow: define the problem and success metrics, collect and clean data, choose appropriate model architecture, specify a loss (e.g., negative log-likelihood), train using SGD or a variant, tune hyperparameters on a validation set, evaluate on a held-out test set, and deploy. Iteration, diagnostics, and error analysis are essential throughout this lifecycle.</p>

        <h3 id="u1-challenges" class="topic">Challenges Motivating Deep Learning</h3>
        <div class="ref">Chapters 1 & 6 — Motivation</div>
        <p>Traditional ML relied heavily on manual feature engineering; deep learning automates hierarchical feature learning, which is especially useful for high-dimensional, structured data like images, speech, and text. Deep models scale with large datasets and modern compute (GPUs/TPUs) and can capture complex, nonlinear relationships that classical models struggle with.</p>

        <h3 id="u1-feed" class="topic">Deep Feedforward Networks (XOR, Gradient Learning, Architecture, Backprop)</h3>
        <div class="ref">Chapter 6 — Deep Feedforward Networks</div>
        <p>The XOR problem historically showed the limits of single-layer perceptrons; stacking hidden layers with nonlinear activations solves such problems by giving models greater representational power. Gradient-based learning trains networks by minimizing a loss via backpropagation, which efficiently computes gradients using the chain rule. Hidden units apply nonlinear activation functions (sigmoid, tanh, ReLU), and architecture design (layers, widths, activations) shapes the hierarchical features learned: early layers capture low-level patterns (edges), intermediate layers detect motifs and parts, and late layers encode high-level concepts.</p>
      </section>

      <!-- UNIT 2 -->
      <section id="unit2" class="card">
        <h2 class="unit">UNIT 2: REGULARIZATION FOR DEEP LEARNING</h2>

        <h3 id="u2-norms" class="topic">Parameter Norm Penalties (L1, L2)</h3>
        <div class="ref">Penalty-based regularization</div>
        <p>Parameter norm penalties add a cost to the loss that grows with the magnitude of model parameters: L2 (weight decay) penalizes squared weights and tends to shrink weights smoothly; L1 encourages sparsity by driving many weights to zero. These penalties reduce overfitting by constraining how large model weights can become, thereby limiting model complexity in a continuous, differentiable way suited for gradient optimization.</p>

        <h3 id="u2-constrained" class="topic">Norm Penalties as Constrained Optimization</h3>
        <div class="ref">Equivalent formulations</div>
        <p>Adding a norm penalty to the objective is equivalent (via Lagrange multipliers) to optimizing under a constraint that the norm of parameters stays below a threshold. Viewing regularization as constrained optimization helps reason about tradeoffs: tightening the constraint reduces model flexibility (reduces variance) but can increase bias if too restrictive.</p>

        <h3 id="u2-underc" class="topic">Regularization for Under-Constrained Problems</h3>
        <div class="ref">Structure when data is scarce</div>
        <p>When training data is limited relative to model capacity, the learning problem is under-constrained—many parameter configurations can explain the data. Regularization injects prior knowledge or inductive biases (smoothness, sparsity, invariances) that guide the model toward plausible solutions and prevent arbitrary memorization.</p>

        <h3 id="u2-aug" class="topic">Dataset Augmentation & Noise Robustness</h3>
        <div class="ref">Data-level regularization</div>
        <p>Dataset augmentation expands training examples by applying label-preserving transformations (rotations, flips, crops, color jitter). This effectively teaches invariances and increases the diversity of training samples. Adding noise to inputs or hidden units (e.g., Gaussian noise, input corruption) trains the model to be robust to perturbations and improves generalization by smoothing the learned function.</p>

        <h3 id="u2-ssl-mtl" class="topic">Semi-Supervised Learning & Multi-Task Learning</h3>
        <div class="ref">Using unlabeled data and shared structure</div>
        <p>Semi-supervised learning leverages large amounts of unlabeled data alongside labeled samples — methods include pseudo-labeling, consistency regularization, and generative modeling — to improve feature learning when labels are expensive. Multi-task learning trains a single model on related tasks, sharing internal representations; this often acts as a regularizer (auxiliary supervision) and improves performance on tasks with limited data by transferring knowledge across tasks.</p>

        <h3 id="u2-early" class="topic">Early Stopping, Parameter Tying and Sharing</h3>
        <div class="ref">Simple but powerful techniques</div>
        <p>Early stopping halts training when validation error ceases improving; this implicitly limits effective capacity by preventing over-training. Parameter tying/sharing (for example, convolutional filters shared across spatial positions) enforces symmetry and reduces the number of free parameters — a powerful inductive bias in vision and sequence models that also reduces overfitting.</p>

        <h3 id="u2-sparse" class="topic">Sparse Representations</h3>
        <div class="ref">Encouraging sparsity</div>
        <p>Sparse representations use penalties or architectures to ensure only a small fraction of units are active for any input (e.g., L1 on activations or sparse autoencoders). Sparsity can increase interpretability, reduce interference between features, and mimic biological coding, which often aids generalization and efficiency.</p>

        <h3 id="u2-ensembles" class="topic">Bagging and Other Ensemble Methods</h3>
        <div class="ref">Reducing variance via model combinations</div>
        <p>Bagging (bootstrap aggregating) trains multiple models on resampled datasets and averages predictions; ensembles typically reduce variance and improve robustness by aggregating diverse model opinions. Practical ensembles range from simple averaging to complex stacking and are widely used in competitions to boost performance.</p>

        <h3 id="u2-dropout" class="topic">Dropout</h3>
        <div class="ref">Stochastic neuron removal during training</div>
        <p>Dropout randomly zeros activations of units during training, preventing complex co-adaptations among neurons. At test time, the full network is used but activations are scaled to account for the training-time dropout. Dropout behaves like model averaging over a large family of thinned networks and often improves generalization in fully connected layers.</p>

        <h3 id="u2-adv" class="topic">Adversarial Training, Tangent Methods & Manifold Tangent Classifier</h3>
        <div class="ref">Robustness and invariance-based regularizers</div>
        <p>Adversarial training augments the training set with inputs perturbed in worst-case directions (adversarial examples), teaching robustness to small but harmful perturbations. Tangent propagation and tangent distance methods encode known invariances (e.g., small rotations, translations) by penalizing sensitivity in those directions. Manifold tangent classifiers attempt to use the local geometry (tangent space) of data manifolds to build classifiers that are invariant along manifold directions.</p>

        <h3 id="u2-opt" class="topic">Optimization for Training Deep Models, Initialization & Adaptive Algorithms</h3>
        <div class="ref">How optimization and initialization interact with regularization</div>
        <p>Optimization choices (learning rate schedules, SGD variants) and parameter initialization strategies strongly affect training dynamics and generalization. Poor initialization can cause vanishing or exploding gradients; careful schemes (Xavier/Glorot, He initialization) help. Adaptive optimizers (AdaGrad, RMSProp, Adam) adjust learning rates per-parameter, often speeding convergence, though practitioners sometimes combine them with additional regularization or switch to SGD with momentum later for final tuning.</p>
      </section>

      <!-- UNIT 3 -->
      <section id="unit3" class="card">
        <h2 class="unit">UNIT 3: CONVOLUTIONAL NETWORKS</h2>

        <h3 id="u3-conv" class="topic">The Convolution Operation</h3>
        <div class="ref">Core operation for grid-structured inputs</div>
        <p>Convolution applies local filters (kernels) across an input grid (image, spectrogram), producing feature maps that detect local patterns such as edges, textures, or motifs. Each filter is learned and applied at every spatial position (weight sharing), enabling the network to detect the same feature regardless of position. Convolutional layers preserve spatial relationships and reduce parameter count compared to fully connected layers, making them highly effective for vision and other structured data.</p>

        <h3 id="u3-motivation" class="topic">Motivation: Locality and Weight Sharing</h3>
        <div class="ref">Why convolution is efficient</div>
        <p>The convolutional paradigm leverages locality (nearby pixels are strongly correlated) and translational symmetry (features can appear anywhere). By connecting each unit to a local receptive field and sharing weights across locations, CNNs reduce parameters, improve statistical efficiency, and encode a strong prior suitable for images and similar data.</p>

        <h3 id="u3-pool" class="topic">Pooling</h3>
        <div class="ref">Downsampling and invariance</div>
        <p>Pooling (e.g., max or average pooling) downsamples feature maps, reducing spatial resolution while retaining salient information. Pooling introduces invariance to small translations and deformations, reduces computation in later layers, and helps aggregate local evidence into more global features. Modern architectures sometimes replace pooling with strided convolutions or learnable downsampling.</p>

        <h3 id="u3-strong" class="topic">Convolution and Pooling as Strong Priors</h3>
        <div class="ref">Built-in inductive biases</div>
        <p>By design, convolution and pooling hard-code assumptions (locality, stationarity, translation invariance) into the model. These are “strong priors” that favor solutions consistent with common image statistics; when these assumptions hold, CNNs learn faster and generalize better than fully connected models.</p>

        <h3 id="u3-variants" class="topic">Variants of the Basic Convolution Function</h3>
        <div class="ref">Dilated, transposed, depthwise, separable</div>
        <p>Architectural variants extend convolution: dilated convolutions increase receptive fields without more parameters; transposed (deconvolution) layers perform learned upsampling for generative or segmentation tasks; depthwise separable convolutions (used in MobileNet) split spatial and cross-channel processing to reduce computation; group convolutions and 1x1 convolutions are used for efficient channel mixing and bottlenecking.</p>

        <h3 id="u3-structured" class="topic">Structured Outputs & Data Types</h3>
        <div class="ref">From classification to dense prediction</div>
        <p>CNNs produce structured outputs such as segmentation masks (per-pixel labels), bounding boxes (detection), heatmaps (keypoint localization), or sequence features (audio/text). While originally built for images, convolutional architectures apply to many data types: audio spectrograms, time-series, video (spatio-temporal convolutions), and even graphs (with graph convolutions adapted for irregular domains).</p>

        <h3 id="u3-efficient" class="topic">Efficient Convolution Algorithms & Unsupervised Features</h3>
        <div class="ref">Speedups and feature learning</div>
        <p>Efficient implementations (FFT-based convolution, Winograd algorithms) and GPU/TPU acceleration make large-scale CNN training feasible. Unsupervised or self-supervised pretraining (autoencoders, contrastive learning) can learn useful convolutional filters from unlabeled data, which are then fine-tuned for downstream tasks to improve performance when labeled data is limited.</p>
      </section>

      <!-- UNIT 4 -->
      <section id="unit4" class="card">
        <h2 class="unit">UNIT 4: RECURRENT AND RECURSIVE NETS</h2>

        <h3 id="u4-unfold" class="topic">Unfolding Computational Graphs</h3>
        <div class="ref">Time-expanded view of recurrence</div>
        <p>RNNs process sequences by recurrently updating a hidden state. Unfolding (or unrolling) the network across time shows it as a deep feedforward network with shared parameters at each time step; this makes gradient computation via backpropagation through time (BPTT) explicit. Unfolding clarifies how gradients propagate and why long-term dependencies can be hard to learn.</p>

        <h3 id="u4-rnns" class="topic">Recurrent Neural Networks & Bidirectional RNNs</h3>
        <div class="ref">Sequential modeling</div>
        <p>Basic RNNs update a hidden state using the current input and previous state, enabling the modeling of temporal dependencies. Bidirectional RNNs run two RNNs (forward and backward) and concatenate their states, giving access to both past and future context — valuable in tasks like speech recognition or sequence labeling where future context improves predictions.</p>

        <h3 id="u4-encoder" class="topic">Encoder–Decoder Sequence-to-Sequence Architectures</h3>
        <div class="ref">Mapping sequences to sequences</div>
        <p>Encoder–decoder architectures use one network (encoder) to read an input sequence into a fixed or attention-weighted representation, and another (decoder) to produce an output sequence. This paradigm underlies machine translation, summarization, and many sequence transduction tasks; attention mechanisms later improved the ability to handle long inputs by letting the decoder access encoder states directly.</p>

        <h3 id="u4-deeprec" class="topic">Deep Recurrent & Recursive Networks</h3>
        <div class="ref">Stacking and tree structures</div>
        <p>Deep RNNs stack multiple recurrent layers for richer temporal representations, analogous to depth in feedforward nets. Recursive neural networks generalize recurrence to tree-structured inputs (e.g., parse trees) by applying the same composition function recursively along a structure rather than linear time steps.</p>

        <h3 id="u4-long" class="topic">The Challenge of Long-Term Dependencies</h3>
        <div class="ref">Vanishing and exploding gradients</div>
        <p>Vanishing gradients (gradients shrink exponentially across many time steps) make learning long-range dependencies difficult; exploding gradients cause instability. Techniques like gradient clipping, careful initialization, gating, and shortcut connections mitigate these problems.</p>

        <h3 id="u4-echo" class="topic">Echo State Networks & Leaky Units</h3>
        <div class="ref">Reservoir computing and slow dynamics</div>
        <p>Echo state networks use a randomly connected recurrent reservoir whose weights are fixed; only the output (readout) layer is trained, simplifying optimization. Leaky units (or leaky integration) combine new inputs slowly with previous states, enabling modeling of multiple time scales and giving the network a form of memory without complex gating.</p>

        <h3 id="u4-lstm" class="topic">LSTMs, GRUs & Gated RNNs</h3>
        <div class="ref">Gates to manage information flow</div>
        <p>LSTMs and GRUs introduce gating mechanisms (input, forget, output gates; update and reset gates) that control what information is written, kept, or read from memory, effectively alleviating vanishing gradient issues and enabling learning of long-term dependencies. These gated cells remain the backbone of many sequence tasks, though attention-based architectures (Transformers) have become dominant in some domains.</p>

        <h3 id="u4-opt" class="topic">Optimization for Long-Term Dependencies & Explicit Memory</h3>
        <div class="ref">Training tricks and memory-augmented models</div>
        <p>Optimization strategies for RNNs include gradient clipping, truncated BPTT, layer normalization, and better initialization. Explicit memory architectures (Neural Turing Machines, Memory Networks) provide read/write mechanisms separate from the recurrent state to store and retrieve longer-term information, at the cost of more complex training dynamics.</p>
      </section>

      <!-- UNIT 5 -->
      <section id="unit5" class="card">
        <h2 class="unit">UNIT 5: PRACTICAL METHODOLOGY</h2>

        <h3 id="u5-metrics" class="topic">Performance Metrics</h3>
        <div class="ref">Choose metrics by task and cost</div>
        <p>Select metrics that align with task goals and business costs: classification often uses accuracy, precision, recall, and F1; imbalanced tasks benefit from ROC AUC or precision-recall curves; detection tasks use mAP; ranking uses NDCG. Always inspect confusion matrices and error types, not only aggregate numbers.</p>

        <h3 id="u5-baseline" class="topic">Default Baseline Models</h3>
        <div class="ref">Start simple</div>
        <p>Begin with simple baselines (majority class, linear models, small trees) to set performance floors and to detect data or label issues. Strong baselines prevent overclaiming the benefit of complex models and help determine whether gains from deep models are meaningful.</p>

        <h3 id="u5-data" class="topic">Determining Whether to Gather More Data</h3>
        <div class="ref">When more data helps</div>
        <p>If model performance is limited by variance (overfitting), more labeled data can significantly improve generalization. Learning curves (error vs. training set size) help identify whether additional data is likely to help. Data augmentation and semi/self-supervised methods are alternatives if labeling more data is costly.</p>

        <h3 id="u5-hp" class="topic">Selecting Hyperparameters</h3>
        <div class="ref">Search strategies and best practices</div>
        <p>Hyperparameter tuning uses strategies like random search, grid search, Bayesian optimization, or hyperband. Important hyperparameters include learning rate, batch size, weight decay, dropout rate, and architecture choices. Log experiments, use validation curves, and prefer reproducible pipelines to make tuning efficient and interpretable.</p>

        <h3 id="u5-debug" class="topic">Debugging Strategies</h3>
        <div class="ref">Ablation, visualizations, and sanity checks</div>
        <p>Debugging deep models includes gradient checking, learning on tiny datasets (sanity check that the model can overfit a few examples), visualizing activations/filters, ablation studies to measure component impact, and detailed error analysis by examining representative failure cases. These practices pinpoint issues and guide fixes.</p>

        <h3 id="u5-case" class="topic">Example: Multi-Digit Number Recognition (Case Study)</h3>
        <div class="ref">Applied methodology</div>
        <p>Multi-digit recognition (e.g., recognizing house numbers) demonstrates the full pipeline: define problem and metrics (per-digit vs sequence accuracy), collect/augment data, choose model (CNN + sequence decoder or CTC-based model), train with regularization and learning-rate schedules, perform validation-driven hyperparameter tuning, and iterate using visualization and error analysis. The case highlights combining architecture design with methodological rigor to improve real-world performance.</p>
      </section>

      <footer>
        <div>Generated summary: concise, chapter-aligned explanations for Units 1–5 (Goodfellow, Bengio & Courville).</div>
        <div style="margin-top:6px">If you want: I can (1) extract these styles to an external CSS file, (2) add printable PDF export, (3) include page-numbered references exactly matching your edition, or (4) expand any topic into longer multi-paragraph subchapters — tell me which.</div>
      </footer>
    </main>
  </div>

  <script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', function(e){
        const id = this.getAttribute('href').slice(1);
        const el = document.getElementById(id);
        if(el){
          e.preventDefault();
          el.scrollIntoView({behavior:'smooth', block:'start'});
        }
      });
    });
  </script>
</body>
</html>
