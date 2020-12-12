const Plugins = [
  // AdminLTE
  {
    from: "node_modules/admin-lte/dist",
    to: "public/plugins/admin-lte"
  },
  // Bootstrap
  {
    from: "node_modules/bootstrap/dist",
    to: "public/plugins/bootstrap"
  },
  // Bootstrap notify
  {
    from: "node_modules/bootstrap-notify/bootstrap-notify.js",
    to: "public/plugins/bootstrap-notify/bootstrap-notify.js",
  },
  {
    from: "node_modules/bootstrap-notify/bootstrap-notify.min.js",
    to: "public/plugins/bootstrap-notify/bootstrap-notify.min.js",
  },
  // Font Awesome
  {
    from: "node_modules/@fortawesome/fontawesome-free/css",
    to: "public/plugins/fontawesome-free/css"
  },
  {
    from: "node_modules/@fortawesome/fontawesome-free/webfonts",
    to: "public/plugins/fontawesome-free/webfonts"
  },
  // iCheck
  {
    from: "node_modules/icheck-bootstrap",
    to: "public/plugins/icheck-bootstrap"
  },
  // jQuery
  {
    from: "node_modules/jquery/dist",
    to: "public/plugins/jquery"
  }
];

module.exports = Plugins;
