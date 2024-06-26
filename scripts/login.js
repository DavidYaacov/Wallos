document.addEventListener('DOMContentLoaded', function () {

    if (window.update_theme_settings) {
      const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const themePreference = prefersDarkMode ? 'dark' : 'light';
      const darkThemeCss = document.querySelector("#dark-theme");
      darkThemeCss.disabled = themePreference === 'light';
      document.body.className = themePreference;
      const themeColorMetaTag = document.querySelector('meta[name="theme-color"]');
      themeColorMetaTag.setAttribute('content', themePreference === 'dark' ? '#222222' : '#FFFFFF');
      const logoImage = document.querySelector('#wallos-logo');
      const imageName = themePreference === 'dark' ? 'walloswhite' : 'wallos';
      const colorTheme = window.color_theme ?? 'blue';
      logoImage.src = `images/siteicons/${colorTheme}/${imageName}.png`;

    }

});