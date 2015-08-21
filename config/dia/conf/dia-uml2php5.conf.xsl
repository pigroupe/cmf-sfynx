<?xml version="1.0"?>
<!-- 
     Config File for UML2PHP5 

     Copyright(c) 2003-2004 KDO <kdo@zpmag.com>

     This program is free software; you can redistribute it and/or modify
     it under the terms of the GNU General Public License as published by
     the Free Software Foundation; either version 2 of the License, or
     (at your option) any later version.
     
     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.
     
     You should have received a copy of the GNU General Public License
     along with this program; if not, write to the Free Software
     Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.

-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

	<!--
		Parameter	: INDENT_STR
		Values		: &#x9; [TAB] or SPACE CHAR(s)
		Comment		: if you want you can replace [TAB] with [SPACE] char(s)
	-->
	<xsl:param name="INDENT_STR"><xsl:text>&#x9;</xsl:text></xsl:param>

	<!--
		Parameter	: CLOSE_TAG
		Values		: ON / OFF
		Comment		: if you want source code ended or not by '?>'
 	-->
	<xsl:param name="CLOSE_TAG">ON</xsl:param>
	
 	<!--
		Parameter	: CLASS_FILE_EXTENSION
		Values		: .class.php / whatever you want
		Comment		: define file extension for classes
 	-->
	<xsl:param name="CLASS_FILE_EXTENSION">.php</xsl:param>
	
 	<!--
		Parameter	: INTERFACE_FILE_EXTENSION
		Values		: .interface.php / whatever you want
		Comment		: define file extension for interfaces
 	-->
	<xsl:param name="INTERFACE_FILE_EXTENSION">.php</xsl:param>
	
 	<!--
		Parameter	: GENERATE_DOC_TAGS
		Values		: ON / OFF
		Comment		: if you want document your source code or not
 	-->
	<xsl:param name="GENERATE_DOC_TAGS">ON</xsl:param>

 	<!--
		Parameter	: COMPOSITION_IMPLICIT_NAMING
		Values		: ON / OFF
		Comment		: generate or not composition attribute if role is missing
 	-->
	<xsl:param name="COMPOSITION_IMPLICIT_NAMING">ON</xsl:param>

 	<!--
		Parameter	: AGGREGATION_IMPLICIT_NAMING
		Values		: ON / OFF
		Comment		: generate or not aggregation attribute and method
 	-->
	<xsl:param name="AGGREGATION_IMPLICIT_NAMING">ON</xsl:param>
 	<!--
		Parameter	: AUTO_EXPAND_INTERFACES
		Values		: ON / OFF
		Comment		: generate interface methods automaticaly
 	-->
	<xsl:param name="AUTO_EXPAND_INTERFACES">OFF</xsl:param>
 	<!--
		Parameter	: _AUTHOR_
		Values		: Name <email>
		Comment		: define the content of @author tag
 	-->
	<xsl:param name="_AUTHOR_">Etienne de Longeaux &lt;etienne.delongeaux@gmail.com&gt;</xsl:param>
 	<!--
		Parameter	: _COPYRIGHT_
		Values		: Any
		Comment		: define the content of @copyright tag
 	-->
	<xsl:param name="_COPYRIGHT_">SFYNX</xsl:param>
 	<!--
		Parameter	: _LICENSE_
		Values		: URL Name
		Comment		: define the content of @license tag
 	-->
	<xsl:param name="_LICENSE_">http://www.gnu.org/licenses</xsl:param>
 	<!--
		Parameter	: TRANSLATE_CONSTRUCTOR
		Values		: ON / OFF
		Comment		: translate the name of the constructor to __construct
 	-->
	<xsl:param name="TRANSLATE_CONSTRUCTOR">ON</xsl:param>
 	<!--
		Parameter	: TRANSLATE_DESTRUCTOR
		Values		: ON / OFF
		Comment		: translate the name of the ~destructor to __destruct
 	-->
	<xsl:param name="TRANSLATE_DESTRUCTOR">ON</xsl:param>
 	<!--
		Parameter	: _CR
		Values		: Linux : &#xa; Windows : &#xd;&#xa;
		Comment		: Define cariage return/Line feed
 	-->
	<xsl:param name="_CR"><xsl:text>&#xa;</xsl:text></xsl:param>
 	<!--
		Parameter	: AUTO_SETTERS_GETTERS
		Values		: ON/OFF (default: OFF)
		Comment		: allows generation of setters/getters for private data
 	-->
	<xsl:param name="AUTO_SETTERS_GETTERS"><xsl:text>ON</xsl:text></xsl:param>
 	<!--
		Parameter	: SOAP_SERVER_URL
		Values		: Any (default: URL/)
		Comment		: SOAP server url (ie: xxxx.yyyy.zzz/ )
 	-->
	<xsl:param name="SOAP_SERVER_URL"><xsl:text>URL/</xsl:text></xsl:param>
</xsl:stylesheet>