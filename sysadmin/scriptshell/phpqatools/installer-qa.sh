#!/bin/sh

# Mise à jour des extensions PEAR déjà installées
sudo pear upgrade-all
sudo pear config-set auto_discover 1

# Installation de quelques extensions supplémentaires (pas forcément toujours utilisées, mais suffisament "souvent" pour que je les installe "par défaut")
sudo pear config-set preferred_state beta
sudo pear install --alldeps PHP_CodeSniffer PhpDocumentor php_CompatInfo Log Text_Diff HTML_QuickForm2 Image_GraphViz MDB2 Mail_Mime PHP_Beautifier-beta SOAP XML_Beautifier XML_RPC Structures_Graph components.ez.no/Graph VersionControl_SVN-alpha Horde_Text_Diff XML_RPC2 VersionControl_Git-alpha

# PHPUnit
sudo pear channel-discover pear.phpunit.de
sudo pear install --alldeps phpunit/PHPUnit

# Phing
sudo pear channel-discover pear.phing.info
sudo pear install --alldeps phing/phing

# Autres outils "QA"
sudo pear channel-discover pear.pdepend.org
sudo pear channel-discover pear.phpmd.org
sudo pear install pdepend/PHP_Depend
sudo pear install phpmd/PHP_PMD
sudo pear install phpunit/phpcpd
sudo pear install phpunit/File_Iterator
sudo pear install phpunit/phploc
sudo pear install --alldeps phpunit/PHP_CodeBrowser

# DocBlox (en alternative plus récente (et compatible PHP 5.3) à PhpDocumentor)
sudo pear channel-discover pear.docblox-project.org
sudo pear install --alldeps docblox/DocBlox
