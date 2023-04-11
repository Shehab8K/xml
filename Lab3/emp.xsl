<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="2.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/contract">
 <html>
  <body>
    <h2>Employees</h2>
    <table border="1">
    <tr bgcolor="#9acd32">
      <th align="left">Name</th>
      <th align="left">Phone</th>
      <th align="left">Email</th>
      <th align="left">Street</th>
      <th align="left">Building Number</th>
	  <th align="left">Region</th>
	  <th align="left">City</th>
	  <th align="left">Country</th>
    </tr>
    <xsl:for-each select="employee">
    <tr>
      <td><xsl:value-of select="name"/></td>
          <xsl:for-each select="phones">
          <td><xsl:value-of select="phone"/></td>
          </xsl:for-each>
      <td><xsl:value-of select="email"/></td>
      
       <xsl:for-each select="addresses">
        <td><xsl:value-of select="address/street"/></td>
        <td><xsl:value-of select="address/building-number"/></td>
        <td><xsl:value-of select="address/region"/></td>
        <td><xsl:value-of select="address/city"/></td>
        <td><xsl:value-of select="address/country"/></td>
        </xsl:for-each>
    </tr>
    </xsl:for-each>
    </table>
  </body>
 </html>
</xsl:template>
</xsl:stylesheet>