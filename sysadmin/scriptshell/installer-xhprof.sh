#!/bin/bash
. `dirname $0`/env.sh

# Installation et configuration XHProf
sudo pecl install xhprof

sudo mkdir /tmp/xhprof
sudo chmod o+w /tmp/xhprof

sudo sh -c "cat > /etc/php5/conf.d/xhprof.ini" <<EOF
extension=xhprof.so
xhprof.output_dir="/tmp/xhprof"
EOF

# install graphviz
sudo apt-get -y install graphviz
sudo apt-get clean

[ ! -d www ] && mkdir -f ${INSTALL_USERHOME}/www
mkdir ${INSTALL_USERHOME}/www/xhprof

xhprof_tgz=`ls /tmp/pear/download/xhprof-*`
cp ${xhprof_tgz} ${INSTALL_USERHOME}/www/xhprof/

xhprof_tgz_home=`ls ${INSTALL_USERHOME}/www/xhprof/xhprof*.tgz`
tar -xvf ${xhprof_tgz_home}
rm ${xhprof_tgz_home}
rm ${INSTALL_USERHOME}/www/xhprof/package.xml

xhprof_dir=${INSTALL_USERHOME}/www/xhprof/`ls ${INSTALL_USERHOME}/www/xhprof/ | grep 'xhprof-'`
cp -R ${xhprof_dir}/xhprof_html ${INSTALL_USERHOME}/www/xhprof/
cp -R ${xhprof_dir}/xhprof_lib ${INSTALL_USERHOME}/www/xhprof/
rm -R ${xhprof_dir} 


cat > ${INSTALL_USERHOME}/www/xhprof/header.php <<EOF
<?php
if (
    (
        (isset(\$_GET['XHPROF']) && \$_GET['XHPROF'])
        || (isset(\$_POST['XHPROF']) && \$_POST['XHPROF'])
        || (isset(\$_COOKIE['XHPROF']) && \$_COOKIE['XHPROF'])
    )
    && extension_loaded('xhprof')) {
    require_once 'xhprof_lib/utils/xhprof_lib.php';
    require_once 'xhprof_lib/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}
EOF

cat > ${INSTALL_USERHOME}/www/xhprof/footer.php <<EOF
<?php
if (
    (
        (isset(\$_GET['XHPROF']) && \$_GET['XHPROF'])
        || (isset(\$_POST['XHPROF']) && \$_POST['XHPROF'])
        || (isset(\$_COOKIE['XHPROF']) && \$_COOKIE['XHPROF'])
    )
    && extension_loaded('xhprof')) {
    \$profiler_namespace = 'myapp';  // namespace for your application
    \$xhprof_data = xhprof_disable();
    \$xhprof_runs = new XHProfRuns_Default();
    \$run_id = \$xhprof_runs->save_run(\$xhprof_data, \$profiler_namespace);
 
    // url to the XHProf UI libraries (change the host name and path)
    \$profiler_url = sprintf('http://%s/xhprof/xhprof_html/index.php?run=%s&source=%s', \$_SERVER['HTTP_HOST'], \$run_id, \$profiler_namespace);
    echo '<a href="'. \$profiler_url .'" target="_blank">Profiler output</a>';
}
EOF

cat >> ${INSTALL_USERHOME}/www/.htaccess <<EOF
php_value auto_prepend_file ${INSTALL_USERHOME}/www/xhprof/header.php
php_value auto_append_file ${INSTALL_USERHOME}/www/xhprof/footer.php
EOF
