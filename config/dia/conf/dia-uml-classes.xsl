<?xml version="1.0"?>
<!-- 
	Transform dia UML objects to a convenient structure
     
	Copyright(c) 2003 KDO <kdo@zpmag.com>     
		
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
  xmlns:dia="http://www.lysator.liu.se/~alla/dia/"
  version="1.0">
  <xsl:output method="xml" indent="yes"/>

  <xsl:template match="/">
    <xsl:element name="dia-uml">
	 <!--
      <xsl:choose>
        <xsl:when test="*/*/*/dia:object[@type='UML - LargePackage']">
          <xsl:apply-templates select="*/*/*/dia:object[@type='UML - LargePackage']"/>      
        </xsl:when>
        <xsl:otherwise>
		-->
          <xsl:apply-templates/>
	<!--
			</xsl:otherwise>
      </xsl:choose>
	-->
    </xsl:element>
  </xsl:template>

	<!-- BEGIN GENERALIZATION LINKS -->
	<xsl:template match="dia:object[@type='UML - Generalization']/dia:connections">
		<xsl:element name="inherit">
			<xsl:for-each select="dia:connection">
				<xsl:if test="@handle='0'">
					<xsl:attribute name="pid">
						<xsl:value-of select="@to"/>            
					</xsl:attribute>
				</xsl:if>
				<xsl:if test="@handle='1'">
					<xsl:attribute name="cid">
						<xsl:value-of select="@to"/>            
					</xsl:attribute>
				</xsl:if>
			</xsl:for-each>
		</xsl:element>
	</xsl:template>
	<!-- END GENERALIZATION LINKS -->
	
	<!-- BEGIN REALIZE LINKS -->
	<xsl:template match="dia:object[@type='UML - Realizes']/dia:connections">
		<xsl:element name="realize">
			<xsl:for-each select="dia:connection">
				<xsl:if test="@handle='0'">
					<xsl:attribute name="pid">
						<xsl:value-of select="@to"/>            
					</xsl:attribute>
				</xsl:if>
				<xsl:if test="@handle='1'">
					<xsl:attribute name="cid">
						<xsl:value-of select="@to"/>            
					</xsl:attribute>
				</xsl:if>
			</xsl:for-each>
		</xsl:element>
	</xsl:template>
	<!-- END REALIZE LINKS -->
	
	<!-- BEGIN ASSOCIATION LINKS -->
	<xsl:template match="dia:object[@type='UML - Association']/dia:attribute[@name='ends']">
		<xsl:choose>
			<xsl:when test="dia:composite/dia:attribute[@name='aggregate']/dia:enum[@val='2']">
				<xsl:call-template name="COMPOSITION"/>
			</xsl:when>
			<xsl:when test="dia:composite/dia:attribute[@name='aggregate']/dia:enum[@val='1']">
				<xsl:call-template name="AGGREGATION"/>
			</xsl:when>
			<xsl:when test="dia:composite/dia:attribute[@name='aggregate']/dia:enum[@val='0']">
				<xsl:call-template name="ASSOCIATION"/>
			</xsl:when>
		</xsl:choose>
	</xsl:template>
	<!-- END ASSOCIATION LINKS -->

	<xsl:template name ="COMPOSITION">
		<xsl:element name="composition">
				<xsl:for-each select="dia:composite">
					<xsl:variable name="pos" select="position()-1"/>
					<xsl:variable name="whole" select="1-$pos"/>
					<xsl:if test="dia:attribute[@name='aggregate']/dia:enum[@val='0']">
						<xsl:attribute name="CMPCID">
							<xsl:value-of select="../../dia:connections/dia:connection[@handle=$whole]/@to"/>            
						</xsl:attribute>
						<xsl:attribute name="CMPPID">
							<xsl:value-of select="../../dia:connections/dia:connection[@handle=$pos]/@to"/>            
						</xsl:attribute>
						<xsl:call-template name="ROLE">
							<xsl:with-param name="role" select="dia:attribute[@name='role']/dia:string"/>
						</xsl:call-template>
						<xsl:attribute name="CMPMULTI">
							<xsl:variable name="str" select="substring-before(substring-after(dia:attribute[@name='multiplicity']/dia:string, '#'), '#')"/>
							<xsl:value-of select="normalize-space($str)"/>
						</xsl:attribute>
					</xsl:if>
				</xsl:for-each>
		</xsl:element>
	</xsl:template>
	
	<xsl:template name ="AGGREGATION">
		<xsl:element name="aggregation">
				<xsl:for-each select="dia:composite">
					<xsl:variable name="pos" select="position()-1"/>
					<xsl:variable name="whole" select="1-$pos"/>
					<xsl:if test="dia:attribute[@name='aggregate']/dia:enum[@val='0']">
						<xsl:attribute name="AGGCID">
							<xsl:value-of select="../../dia:connections/dia:connection[@handle=$whole]/@to"/>            
						</xsl:attribute>
						<xsl:attribute name="AGGPID">
							<xsl:value-of select="../../dia:connections/dia:connection[@handle=$pos]/@to"/>            
						</xsl:attribute>
						<xsl:call-template name="ROLE">
							<xsl:with-param name="role" select="dia:attribute[@name='role']/dia:string"/>
						</xsl:call-template>
						<xsl:attribute name="AGGMULTI">
							<xsl:variable name="str" select="substring-before(substring-after(dia:attribute[@name='multiplicity']/dia:string, '#'), '#')"/>
							<xsl:value-of select="normalize-space($str)"/>
						</xsl:attribute>
					</xsl:if>
				</xsl:for-each>
		</xsl:element>
	</xsl:template>
	
<xsl:template name ="ASSOCIATION">
	<xsl:variable name="nav" select="count(dia:composite/dia:attribute[@name='arrow']/dia:boolean[@val='false'])"/>
	<xsl:for-each select="dia:composite">
		<xsl:variable name="pos" select="position()-1"/>
		<xsl:variable name="whole" select="1-$pos"/>
		<xsl:variable name="arrow" select="dia:attribute[@name='arrow']/dia:boolean/@val"/>
		<xsl:if test="($arrow='true')or($nav=2)">
			<xsl:element name="association">
				<xsl:attribute name="STARTID">
					<xsl:value-of select="../../dia:connections/dia:connection[@handle=$whole]/@to"/>            
				</xsl:attribute>
			<xsl:attribute name="ENDID">
				<xsl:value-of select="../../dia:connections/dia:connection[@handle=$pos]/@to"/>            
			</xsl:attribute>
			<xsl:call-template name="ROLE">
				<xsl:with-param name="role" select="dia:attribute[@name='role']/dia:string"/>
			</xsl:call-template>
				<xsl:attribute name="MULTI">
					<xsl:variable name="str" select="substring-before(substring-after(dia:attribute[@name='multiplicity']/dia:string, '#'), '#')"/>
					<xsl:value-of select="normalize-space($str)"/>
				</xsl:attribute>
				<xsl:attribute name="NAVIG">
					<xsl:value-of select="$arrow"/>
				</xsl:attribute>
			</xsl:element>
		</xsl:if>
	</xsl:for-each>
</xsl:template>

<xsl:template name="ROLE">
	<xsl:param name="role"/>
	<xsl:attribute name="ROLE">
		<xsl:choose>
		<xsl:when test="$role='##'">
			<xsl:value-of select="substring-before($role, '##')"/>
		</xsl:when>
		<xsl:when test="starts-with($role, '##')">
			<xsl:variable name="str" select="substring-before(substring-after($role, '##'), '#')"/>
			<xsl:value-of select="concat('#',$str)"/>
		</xsl:when>
		<xsl:otherwise>
			<xsl:variable name="str" select="substring-before(substring-after($role, '#'), '#')"/>
			<xsl:value-of select="normalize-space($str)"/>
		</xsl:otherwise>
		</xsl:choose>
	</xsl:attribute>
</xsl:template>

<!--
<xsl:template match="dia:object[@type='UML - LargePackage']">
	<xsl:element name="package">
		<xsl:attribute name="name">
			<xsl:value-of select="substring-before(substring-after(dia:attribute[@name='name']/dia:string, '#'), '#')"/>
		</xsl:attribute>
		<xsl:if test="dia:attribute[@name='stereotype']">
			<xsl:attribute name="stereotype">
				<xsl:value-of select="substring-before(substring-after(dia:attribute[@name='stereotype']/dia:string, '#'), '#')"/>                
			</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates select="../dia:object[@type='UML - Class']"/>  
	</xsl:element>
</xsl:template>
-->
	<xsl:template match="dia:object[@type='UML - Class']">
    <xsl:element name="class">
      <xsl:attribute name="name">
        <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='name']/dia:string, '#'), '#')"/>            
      </xsl:attribute>
      <xsl:attribute name="id">
        <xsl:value-of select="@id"/>            
      </xsl:attribute>
      <xsl:if test="dia:attribute[@name='stereotype']">
        <xsl:attribute name="stereotype">
          <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='stereotype']/dia:string, '#'), '#')"/>                
        </xsl:attribute>
      </xsl:if>
      <xsl:if test="dia:attribute[@name='abstract']/dia:boolean/@val='true'">
        <xsl:attribute name="abstract">1</xsl:attribute>
      </xsl:if>
      <xsl:element name="comment">
	<xsl:value-of select="substring-before(substring-after(dia:attribute[@name='comment']/dia:string, '#'), '#')"/>
      </xsl:element>
      <xsl:element name="attributes">
        <xsl:apply-templates select="dia:attribute[@name='attributes']"/>
      </xsl:element>
      <xsl:element name="operations">
        <xsl:apply-templates select="dia:attribute[@name='operations']"/>
      </xsl:element>
    </xsl:element>    
  </xsl:template>

  <xsl:template match="dia:composite[@type='umlattribute']">
    <xsl:element name="attribute">
      <xsl:if test="dia:attribute[@name='class_scope']/dia:boolean/@val='true'">
        <xsl:attribute name="class_scope">1</xsl:attribute>
      </xsl:if>
      <xsl:choose>
        <xsl:when test="dia:attribute[@name='visibility']/dia:enum/@val=1">
          <xsl:attribute name="visibility">private</xsl:attribute>
        </xsl:when>
        <xsl:when test="dia:attribute[@name='visibility']/dia:enum/@val=2">
          <xsl:attribute name="visibility">protected</xsl:attribute>
        </xsl:when>
        <xsl:when test="dia:attribute[@name='visibility']/dia:enum/@val=0">
          <xsl:attribute name="visibility">public</xsl:attribute>
        </xsl:when>
      </xsl:choose>

      <xsl:element name="type">
        <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='type']/dia:string, '#'), '#')"/>
      </xsl:element>

      <xsl:element name="name">
        <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='name']/dia:string, '#'), '#')"/>
      </xsl:element>

      <xsl:element name="comment">
	<xsl:value-of select="substring-before(substring-after(dia:attribute[@name='comment']/dia:string, '#'), '#')"/>
      </xsl:element>

      <xsl:if test="not(dia:attribute[@name='value']/dia:string='##')">
        <xsl:element name="value">
          <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='value']/dia:string, '#'), '#')"/>
        </xsl:element>
      </xsl:if>
    </xsl:element>
  </xsl:template>
  
  
  <xsl:template match="dia:composite[@type='umloperation']">
    <xsl:element name="operation">
      <xsl:choose>
        <xsl:when test="dia:attribute[@name='inheritance_type']/dia:enum/@val=2">
          <xsl:attribute name="inheritance">leaf</xsl:attribute>
        </xsl:when>
        <xsl:when test="dia:attribute[@name='inheritance_type']/dia:enum/@val=1">
          <xsl:attribute name="inheritance">polymorphic</xsl:attribute>
        </xsl:when>
        <xsl:when test="dia:attribute[@name='inheritance_type']/dia:enum/@val=0">
          <xsl:attribute name="inheritance">abstract</xsl:attribute>
        </xsl:when>
      </xsl:choose>
      <xsl:choose>
        <xsl:when test="dia:attribute[@name='visibility']/dia:enum/@val=1">
          <xsl:attribute name="visibility">private</xsl:attribute>
        </xsl:when>
        <xsl:when test="dia:attribute[@name='visibility']/dia:enum/@val=2">
          <xsl:attribute name="visibility">protected</xsl:attribute>
        </xsl:when>
        <xsl:when test="dia:attribute[@name='visibility']/dia:enum/@val=0">
          <xsl:attribute name="visibility">public</xsl:attribute>
        </xsl:when>
      </xsl:choose>
      
      <xsl:if test="dia:attribute[@name='class_scope']/dia:boolean/@val='true'">
        <xsl:attribute name="class_scope">1</xsl:attribute>
      </xsl:if>

      <xsl:choose>
        <xsl:when test="dia:attribute[@name='query']/dia:boolean/@val='true'">
          <xsl:attribute name="query">1</xsl:attribute>
        </xsl:when>
        <xsl:otherwise>
          <xsl:attribute name="query">0</xsl:attribute>
        </xsl:otherwise>
      </xsl:choose>
      
      <xsl:if test="not(dia:attribute[@name='type']/dia:string='##')">
        <xsl:element name="type">
          <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='type']/dia:string, '#'), '#')"/>
        </xsl:element>
      </xsl:if>    
      
      <xsl:element name="name">
        <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='name']/dia:string, '#'), '#')"/>
      </xsl:element>

      <xsl:if test="not(dia:attribute[@name='comment']/dia:string='##')">
	<xsl:element name="comment">
	  <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='comment']/dia:string, '#'), '#')"/>
	</xsl:element>
      </xsl:if>
      
      <xsl:if test="dia:attribute[@name='parameters']/dia:composite[@type='umlparameter']">
	<xsl:element name="parameters">
	  <xsl:for-each select="dia:attribute[@name='parameters']/dia:composite[@type='umlparameter']">
	    <xsl:element name="parameter">
	      <xsl:choose>
		<xsl:when test="dia:attribute[@name='kind']/dia:enum/@val=1">
		  <xsl:attribute name="kind">in</xsl:attribute>
		</xsl:when>
		<xsl:when test="dia:attribute[@name='kind']/dia:enum/@val=2">
		  <xsl:attribute name="kind">out</xsl:attribute>
		</xsl:when>
		<xsl:when test="dia:attribute[@name='kind']/dia:enum/@val=3">
		  <xsl:attribute name="kind">inout</xsl:attribute>
		</xsl:when>
	      </xsl:choose>
	      
	      <xsl:element name="type">
		<xsl:value-of select="substring-before(substring-after(dia:attribute[@name='type']/dia:string, '#'), '#')"/>
	      </xsl:element>
	      
	      <xsl:element name="name">
		<xsl:value-of select="substring-before(substring-after(dia:attribute[@name='name']/dia:string, '#'), '#')"/>
	      </xsl:element>
	      
	      <xsl:if test="dia:attribute[@name='comment']">
		<xsl:element name="comment">
		  <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='comment']/dia:string, '#'), '#')"/>
		</xsl:element>
	      </xsl:if>
	      
	      <xsl:if test="not(dia:attribute[@name='value']/dia:string='##')">
		<xsl:element name="value">
		  <xsl:value-of select="substring-before(substring-after(dia:attribute[@name='value']/dia:string, '#'), '#')"/>
		</xsl:element>              
	      </xsl:if>
	      
	    </xsl:element>
	  </xsl:for-each>      
	</xsl:element>
      </xsl:if>
    </xsl:element>
  </xsl:template>

  <xsl:template match="*/*/dia:object[@type='UML - Component']">
    <xsl:element name="component">
      <xsl:if test="not(dia:attribute[@name='stereotype']/dia:string='##')">
	<xsl:attribute name="stereotype"><xsl:value-of 
	  select="substring-before(substring-after(dia:attribute[@name='stereotype']/dia:string, '#'), '#')"/></xsl:attribute>              
      </xsl:if>
      <xsl:if test="not(dia:attribute[@name='text']/dia:composite/dia:attribute/dia:string='##')"><xsl:value-of 
	select="substring-before(substring-after(dia:attribute[@name='text']/dia:composite/dia:attribute/dia:string, '#'), '#')"/>              
      </xsl:if>
    </xsl:element>
  </xsl:template>
  
  <xsl:template match="text()"></xsl:template>

  <xsl:template match="node()|@*">
    <xsl:apply-templates match="node()|@*"/>  
  </xsl:template>

  
</xsl:stylesheet>


<!-- Keep this comment at the end of the file
Local variables:
mode: xml
sgml-omittag:nil
sgml-shorttag:nil
sgml-namecase-general:nil
sgml-general-insert-case:lower
sgml-minimize-attributes:nil
sgml-always-quote-attributes:t
sgml-indent-step:2
sgml-indent-data:t
sgml-parent-document:nil
sgml-exposed-tags:nil
sgml-local-catalogs:nil
sgml-local-ecat-files:nil
End:
-->