/* Public Frontend Interactive UI Script */

document.addEventListener('DOMContentLoaded', () => {
  // 0. Transparent Unique Page Loader Dismissal
  const pageLoader = document.getElementById('page-loader');
  if (pageLoader) {
    const dismissLoader = () => {
      pageLoader.classList.add('opacity-0', 'pointer-events-none');
      setTimeout(() => {
        if (pageLoader && pageLoader.parentNode) {
          pageLoader.parentNode.removeChild(pageLoader);
        }
      }, 500);
    };

    if (document.readyState === 'complete') {
      dismissLoader();
    } else {
      window.addEventListener('load', dismissLoader);
      setTimeout(dismissLoader, 1500);
    }
  }

  // 1. Theme Mode (Dark/Light) Initialization
  const initMode = () => {
    const el = document.documentElement;
    const enableDark = el.getAttribute('data-enable-dark') !== '0';
    const defaultMode = el.getAttribute('data-default-mode') || 'system';
    const stored = localStorage.getItem('theme');
    
    if (enableDark) {
      if (stored === 'dark' || (!stored && defaultMode === 'dark') || (!stored && defaultMode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        el.classList.add('dark');
        el.setAttribute('data-mode', 'dark');
      } else {
        el.classList.remove('dark');
        el.setAttribute('data-mode', 'light');
      }
    }
  };
  initMode();

  // Dark/Light Mode Toggle Event Handlers
  const modeToggles = document.querySelectorAll('.theme-toggle-btn');
  modeToggles.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const isDark = document.documentElement.classList.toggle('dark');
      const mode = isDark ? 'dark' : 'light';
      document.documentElement.setAttribute('data-mode', mode);
      localStorage.setItem('theme', mode);
    });
  });

  // 2. Active Theme Cleanup
  if (localStorage.getItem('active_theme')) {
    localStorage.removeItem('active_theme');
  }

  // 3. Mobile Navigation Menu Toggle
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
  if (mobileMenuBtn && mobileMenuDrawer) {
    mobileMenuBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      mobileMenuDrawer.classList.toggle('hidden');
    });

    const mobileNavLinks = mobileMenuDrawer.querySelectorAll('a');
    mobileNavLinks.forEach(link => {
      link.addEventListener('click', () => {
        mobileMenuDrawer.classList.add('hidden');
      });
    });

    document.addEventListener('click', (e) => {
      if (!mobileMenuDrawer.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
        mobileMenuDrawer.classList.add('hidden');
      }
    });
  }

  // 4. Hero Code Typing Animation
  const codeLines = document.querySelectorAll('.typed-code-line');
  codeLines.forEach((line, index) => {
    const fullText = line.getAttribute('data-text');
    const targetSpan = line.querySelector('.code-val');
    if (!fullText || !targetSpan) return;

    setTimeout(() => {
      let i = 0;
      const interval = setInterval(() => {
        i++;
        targetSpan.textContent = fullText.slice(0, i);
        if (i >= fullText.length) {
          clearInterval(interval);
          const cursor = line.querySelector('.typing-cursor');
          if (cursor) {
            setTimeout(() => cursor.remove(), 600);
          }
        }
      }, 35);
    }, 600 + index * 500);
  });

  // 5. Category Filtering for Projects
  const filterBtns = document.querySelectorAll('.project-filter-btn');
  const projectCards = document.querySelectorAll('.project-card-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const filter = btn.getAttribute('data-filter');

      filterBtns.forEach(b => {
        b.classList.remove('bg-emerald-500', 'text-white', 'shadow-md');
        b.classList.add('bg-slate-200', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
      });
      btn.classList.remove('bg-slate-200', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
      btn.classList.add('bg-emerald-500', 'text-white', 'shadow-md');

      projectCards.forEach(card => {
        const cat = card.getAttribute('data-category');
        if (filter === 'all' || cat === filter) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // 6. Project Modal Trigger Logic
  const modalOverlay = document.getElementById('project-modal-overlay');
  const modalCloseBtns = document.querySelectorAll('.close-project-modal');
  const openModalBtns = document.querySelectorAll('.open-project-modal, .project-card-item');

  const closeModal = () => {
    if (modalOverlay) {
      modalOverlay.classList.add('hidden');
      document.body.style.overflow = '';
      const videoPlayer = document.getElementById('modal-video-player');
      const videoSource = document.getElementById('modal-video-source');
      if (videoPlayer) {
        videoPlayer.pause();
        try { videoPlayer.currentTime = 0; } catch (e) {}
      }
      if (videoSource) {
        videoSource.src = '';
      }
    }
  };

  openModalBtns.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      // Don't open modal if user clicked directly on Demo, GitHub, or Page link
      if (e.target.closest('a[target="_blank"]') || e.target.closest('a[href*="/projects/"]')) return;

      e.preventDefault();
      const card = trigger.closest('.project-card-item') || trigger;
      if (!card || !modalOverlay) return;

      const title = card.getAttribute('data-title') || '';
      const categoryName = card.getAttribute('data-category-name') || 'Project';
      const shortDesc = card.getAttribute('data-short-description') || '';
      const problem = card.getAttribute('data-problem') || '';
      const solution = card.getAttribute('data-solution') || '';
      const architecture = card.getAttribute('data-architecture') || '';
      const challenges = card.getAttribute('data-challenges') || '';
      const learned = card.getAttribute('data-learned') || '';
      const image = card.getAttribute('data-image') || '';
      const videoUrl = card.getAttribute('data-video-url') || '';
      const demoUrl = card.getAttribute('data-demo-url') || '';
      const githubUrl = card.getAttribute('data-github-url') || '';
      const projectSlug = card.getAttribute('data-project-slug') || '';

      let features = [];
      let technologies = [];
      let gallery = [];
      try { features = JSON.parse(card.getAttribute('data-features') || '[]'); } catch (err) {}
      try { technologies = JSON.parse(card.getAttribute('data-technologies') || '[]'); } catch (err) {}
      try { gallery = JSON.parse(card.getAttribute('data-gallery') || '[]'); } catch (err) {}

      // Title & Category
      const modalTitleEl = document.getElementById('modal-title');
      if (modalTitleEl) modalTitleEl.textContent = title;
      
      const modalCatBadgeEl = document.getElementById('modal-category-badge');
      if (modalCatBadgeEl) modalCatBadgeEl.textContent = categoryName;

      // Unified Media Showcase (Video + Images in one slider)
      const mediaItems = [];
      if (videoUrl && videoUrl.trim() !== '') {
        mediaItems.push({ type: 'video', url: videoUrl });
      }
      const allImgs = image ? [image, ...gallery.filter(g => g && g !== image)] : gallery.filter(Boolean);
      allImgs.forEach(imgSrc => {
        mediaItems.push({ type: 'image', url: imgSrc });
      });

      const mediaWrapper = document.getElementById('modal-media-wrapper');
      const videoContainer = document.getElementById('modal-video-container');
      const videoPlayer = document.getElementById('modal-video-player');
      const videoSource = document.getElementById('modal-video-source');
      const mainImg = document.getElementById('modal-main-img');
      const prevBtn = document.getElementById('modal-prev-btn');
      const nextBtn = document.getElementById('modal-next-btn');
      const thumbsContainer = document.getElementById('modal-gallery-thumbs');

      if (mediaItems.length > 0) {
        if (mediaWrapper) mediaWrapper.classList.remove('hidden');

        let currentMediaIdx = 0;

        const updateActiveMedia = (idx) => {
          currentMediaIdx = idx;
          const item = mediaItems[currentMediaIdx];

          if (item.type === 'video') {
            if (mainImg) mainImg.classList.add('hidden');
            if (videoContainer) videoContainer.classList.remove('hidden');
            if (videoSource && videoPlayer) {
              videoSource.src = item.url;
              videoPlayer.load();
              videoPlayer.play().catch(() => {});
            }
          } else {
            if (videoPlayer) videoPlayer.pause();
            if (videoContainer) videoContainer.classList.add('hidden');
            if (mainImg) {
              mainImg.src = item.url;
              mainImg.classList.remove('hidden');
            }
          }

          if (thumbsContainer) {
            Array.from(thumbsContainer.children).forEach((thumb, i) => {
              if (i === currentMediaIdx) {
                thumb.classList.add('border-emerald-500', 'opacity-100');
                thumb.classList.remove('border-transparent', 'opacity-60');
              } else {
                thumb.classList.remove('border-emerald-500', 'opacity-100');
                thumb.classList.add('border-transparent', 'opacity-60');
              }
            });
          }
        };

        if (mediaItems.length > 1) {
          if (prevBtn) prevBtn.classList.remove('hidden');
          if (nextBtn) nextBtn.classList.remove('hidden');

          if (prevBtn) {
            prevBtn.onclick = (ev) => {
              ev.stopPropagation();
              let newIdx = (currentMediaIdx - 1 + mediaItems.length) % mediaItems.length;
              updateActiveMedia(newIdx);
            };
          }
          if (nextBtn) {
            nextBtn.onclick = (ev) => {
              ev.stopPropagation();
              let newIdx = (currentMediaIdx + 1) % mediaItems.length;
              updateActiveMedia(newIdx);
            };
          }

          if (thumbsContainer) {
            thumbsContainer.innerHTML = '';
            mediaItems.forEach((item, idx) => {
              const btn = document.createElement('button');
              btn.type = 'button';
              btn.className = `aspect-video overflow-hidden rounded-lg border-2 ${idx === 0 ? 'border-emerald-500 opacity-100' : 'border-transparent opacity-60'} hover:opacity-100 transition-all cursor-pointer relative bg-slate-900 flex items-center justify-center group`;
              
              if (item.type === 'video') {
                btn.innerHTML = `
                  <div class="absolute inset-0 flex flex-col items-center justify-center bg-indigo-950/80 text-emerald-400">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    <span class="text-[9px] font-mono font-bold uppercase text-white tracking-wider">Video</span>
                  </div>
                `;
              } else {
                btn.innerHTML = `<img src="${item.url}" class="h-full w-full object-cover">`;
              }

              btn.addEventListener('click', () => updateActiveMedia(idx));
              thumbsContainer.appendChild(btn);
            });
            thumbsContainer.classList.remove('hidden');
          }
        } else {
          if (prevBtn) prevBtn.classList.add('hidden');
          if (nextBtn) nextBtn.classList.add('hidden');
          if (thumbsContainer) thumbsContainer.classList.add('hidden');
        }

        updateActiveMedia(0);

      } else {
        if (mediaWrapper) mediaWrapper.classList.add('hidden');
      }

      // Overview
      const setWrapperText = (wrapperId, textId, value) => {
        const wrapper = document.getElementById(wrapperId);
        const text = document.getElementById(textId);
        if (wrapper && text) {
          if (value && value.trim().length > 0) {
            text.textContent = value;
            wrapper.classList.remove('hidden');
          } else {
            wrapper.classList.add('hidden');
          }
        }
      };

      setWrapperText('modal-overview-wrapper', 'modal-short-desc', shortDesc);
      setWrapperText('modal-problem-wrapper', 'modal-problem', problem);
      setWrapperText('modal-solution-wrapper', 'modal-solution', solution);
      setWrapperText('modal-challenges-wrapper', 'modal-challenges', challenges);
      setWrapperText('modal-learned-wrapper', 'modal-learned', learned);

      // Problem / Solution Parent Grid
      const psWrapper = document.getElementById('modal-problem-solution-wrapper');
      if (psWrapper) {
        if ((problem && problem.trim()) || (solution && solution.trim())) {
          psWrapper.classList.remove('hidden');
        } else {
          psWrapper.classList.add('hidden');
        }
      }

      // Architecture Flow
      const archWrapper = document.getElementById('modal-arch-wrapper');
      const archContainer = document.getElementById('modal-arch-container');
      if (archWrapper && archContainer) {
        if (architecture && architecture.trim().length > 0) {
          const steps = architecture.split(/->|→/).map(s => s.trim()).filter(Boolean);
          archContainer.innerHTML = steps.map((step, idx) => `
            <span class="px-3 py-1 rounded-xl bg-indigo-500/10 dark:bg-cyan-500/10 text-indigo-600 dark:text-cyan-400 font-mono font-semibold border border-indigo-500/20 dark:border-cyan-500/20">${step}</span>
            ${idx < steps.length - 1 ? '<span class="text-slate-400 dark:text-slate-500 font-mono font-bold text-sm">→</span>' : ''}
          `).join('');
          archWrapper.classList.remove('hidden');
        } else {
          archWrapper.classList.add('hidden');
        }
      }

      // Features List
      const featWrapper = document.getElementById('modal-features-wrapper');
      const featList = document.getElementById('modal-features-list');
      if (featWrapper && featList) {
        if (features && features.length > 0) {
          featList.innerHTML = features.map(f => `
            <li class="flex items-start gap-2.5 text-slate-700 dark:text-slate-300 text-sm font-sans">
              <svg class="w-4 h-4 mt-0.5 text-indigo-600 dark:text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
              <span>${f}</span>
            </li>
          `).join('');
          featWrapper.classList.remove('hidden');
        } else {
          featWrapper.classList.add('hidden');
        }
      }

      // Tech Stack List
      const techWrapper = document.getElementById('modal-tech-wrapper');
      const techList = document.getElementById('modal-tech-list');
      if (techWrapper && techList) {
        if (technologies && technologies.length > 0) {
          techList.innerHTML = technologies.map(t => `
            <span class="rounded-xl border border-indigo-500/20 dark:border-cyan-500/20 bg-indigo-500/10 dark:bg-cyan-500/10 px-3 py-1 text-xs font-mono font-medium text-indigo-600 dark:text-cyan-400">
              #${t}
            </span>
          `).join('');
          techWrapper.classList.remove('hidden');
        } else {
          techWrapper.classList.add('hidden');
        }
      }

      // Action Buttons
      const demoBtn = document.getElementById('modal-demo-btn');
      const demoDisabled = document.getElementById('modal-demo-disabled');
      if (demoUrl) {
        if (demoBtn) { demoBtn.href = demoUrl; demoBtn.classList.remove('hidden'); }
        if (demoDisabled) demoDisabled.classList.add('hidden');
      } else {
        if (demoBtn) demoBtn.classList.add('hidden');
        if (demoDisabled) demoDisabled.classList.remove('hidden');
      }

      const githubBtn = document.getElementById('modal-github-btn');
      const githubDisabled = document.getElementById('modal-github-disabled');
      if (githubUrl) {
        if (githubBtn) { githubBtn.href = githubUrl; githubBtn.classList.remove('hidden'); }
        if (githubDisabled) githubDisabled.classList.add('hidden');
      } else {
        if (githubBtn) githubBtn.classList.add('hidden');
        if (githubDisabled) githubDisabled.classList.remove('hidden');
      }

      const detailsPageBtn = document.getElementById('modal-details-page-btn');
      if (detailsPageBtn && projectSlug) {
        detailsPageBtn.href = `/projects/${projectSlug}`;
      }

      modalOverlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    });
  });


  modalCloseBtns.forEach(btn => {
    btn.addEventListener('click', closeModal);
  });

  if (modalOverlay) {
    modalOverlay.addEventListener('click', (e) => {
      if (e.target === modalOverlay) closeModal();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });

  // 7. Contact Form AJAX Submission (No Page Reload)
  const contactForms = document.querySelectorAll('form[action*="/contact"]');
  contactForms.forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const submitBtn = form.querySelector('button[type="submit"]');
      const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
          <svg class="w-4 h-4 animate-spin inline-block" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>Sending...</span>
        `;
      }

      const existingAlerts = form.parentElement.querySelectorAll('.ajax-contact-alert');
      existingAlerts.forEach(el => el.remove());

      try {
        const formData = new FormData(form);
        const response = await fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          }
        });

        const data = await response.json();

        if (response.ok && data.success) {
          form.reset();

          const alertDiv = document.createElement('div');
          alertDiv.className = 'ajax-contact-alert mb-6 p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-sm font-semibold backdrop-blur-md flex items-center gap-2';
          alertDiv.innerHTML = `
            <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            <span>${data.message || 'Thank you for your message! I will get back to you soon.'}</span>
          `;

          form.parentElement.insertBefore(alertDiv, form);
          alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
          let errorMsg = data.message || 'Something went wrong. Please check your input and try again.';
          if (data.errors) {
            errorMsg = Object.values(data.errors).flat().join('<br>');
          }

          const alertDiv = document.createElement('div');
          alertDiv.className = 'ajax-contact-alert mb-6 p-4 rounded-2xl bg-red-500/15 border border-red-500/30 text-red-600 dark:text-red-400 text-sm font-semibold backdrop-blur-md flex items-center gap-2';
          alertDiv.innerHTML = `
            <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <div>${errorMsg}</div>
          `;

          form.parentElement.insertBefore(alertDiv, form);
        }
      } catch (err) {
        console.error('Contact Form AJAX Error:', err);
        const alertDiv = document.createElement('div');
        alertDiv.className = 'ajax-contact-alert mb-6 p-4 rounded-2xl bg-red-500/15 border border-red-500/30 text-red-600 dark:text-red-400 text-sm font-semibold backdrop-blur-md flex items-center gap-2';
        alertDiv.innerHTML = `
          <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          <span>Unable to send message. Please try again later.</span>
        `;
        form.parentElement.insertBefore(alertDiv, form);
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnHtml;
        }
      }
    });
  });
});
