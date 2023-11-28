module.exports = {
  defaults: {
    template: true,
    css: true,
    javascript: true,
    vue: true,
    fractal: false,
    vuexModule: false,
  },
  fileHeader: {
    authors: [{
      name: 'Samuel Reichör',
      email: 'samuel@fredmansky.at'
    }],
    projectName: 'ProjectName'
  },
  files: {
    template: {
      name: '',
      postfix: '-template',
      extension: 'html',
      description: 'ferdi should create a Template File'
    },
    css: {
      name: '',
      postfix: '-style',
      extension: 'pcss',
      description: 'ferdi should create Stylesheet File'
    },
    javascript: {
      name: '',
      postfix: '-script',
      extension: 'js',
      description: 'ferdi should create JavaScript File'
    },
    vue: {
      name: '',
      postfix: '',
      extension: 'vue'
    },
    vuexModule: {
      name: '',
      postfix: '',
      extension: 'js',
      path: 'js/store/modules/',
    },
  },
  paths: {
    templateBase: 'ferdi/',
    modulePath: './',
    pathOptions: {
      // the first character of each key works as an alias for the path so you could use `-a` as an alias for atomic
      components: 'components/',
      pages: 'pages/'
    }
  }
};