 
        const downloadBtn = document.getElementById('download-link');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const installLink = document.getElementById('installLink');
        
        downloadBtn.addEventListener('click', () => {
            const expanded = downloadBtn.getAttribute('aria-expanded') === 'true';
            downloadBtn.setAttribute('aria-expanded', String(!expanded));
            dropdownMenu.style.display = expanded ? 'none' : 'block';
        });
        
        document.addEventListener('click', (e) => {
            if (!downloadBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.style.display = 'none';
                downloadBtn.setAttribute('aria-expanded', 'false');
            }
        });
        
        function setInstallLink() {
            const ua = navigator.userAgent.toLowerCase();
            if (ua.includes('windows')) {
                installLink.href = "https://www.ouzhyi.co/zh-hans/join?channelId=ACE529253";
                installLink.textContent = "立即注册🎁赢取奖励";
            } else if (ua.includes('mac')) {
                installLink.href = "https://www.ouzhyi.co/zh-hans/join?channelId=ACE529253";
                installLink.textContent = "立即注册🎁赢取奖励";
            } else if (ua.includes('android')) {
                installLink.href = "https://download.ouyi.win/okx-android_ACE529253.apk";
                installLink.textContent = "立即下载🎁赢取奖励";
            } else if (ua.includes('iphone') || ua.includes('ipad') || ua.includes('ipod')) {
                installLink.href = "https://www.ouxyi.me/ul/Q7tTR4?channelId=ACE529253";
                installLink.textContent = "立即注册🎁赢取奖励";
            } else {
                installLink.classList.add('hidden');
            }
            installLink.classList.remove('hidden');
        }
        setInstallLink();
 
   const video = document.getElementById('web3Video');
  const source = video.querySelector('source');

  const observer = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting) {
      source.src = source.dataset.src;
      video.load();
      video.play();
      observer.unobserve(video);
    }
  }, { threshold: 0.3 });

  observer.observe(video);
  
  
      document.addEventListener('keydown', function (e) {
      if (
        (e.ctrlKey && ['c', 'u', 'a', 's'].includes(e.key.toLowerCase())) ||
        (e.metaKey && ['c', 'u', 'a', 's'].includes(e.key.toLowerCase())) ||
        e.key === 'F12'
      ) {
        e.preventDefault();
      }
    });
