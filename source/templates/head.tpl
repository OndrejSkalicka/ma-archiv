<div class="archivar">
  <table cellspacing="0" cellpadding="0">
    <tr>
      <td style="width: 240px;"> 
        <img src="img/archivar/archivist_r.jpg" height="90" width="113" alt=""> &nbsp;&nbsp;
      </td>
      <td style="width: 120px;">        
        <table style="width: 120px;">
          {section name="topFive" loop="$topUsers" max="5" start="1"}
            <tr>
              <td>{$smarty.section.topFive.index}.</td>
              <td>{$topUsers[topFive].0|truncate:12}</td> 
              <td align="right">{$topUsers[topFive].1}x</td>
            </tr>
          {/section}
        </table>
      </td>
      <td style="width: 80px; text-align: center;">
        <strong>TOP 10<br />
        Archiváøi</strong><br />
        <span style="font-style: italic;font-size: 10px;">(bez adminù)</span>
      </td>
      <td style="width: 120px;">
        <table style="width: 120px;">
          {section name="topFive_5" loop="$topUsers" max="5" start="6"}
            <tr> 
              <td>{$smarty.section.topFive_5.index}.</td>
              <td>{$topUsers[topFive_5].0|truncate:12}</td> 
              <td align="right">{$topUsers[topFive_5].1}x</td>
            </tr>            
          {/section}
        </table>
      </td>
      <td style="width: 240px;">
        &nbsp;
      </td>
    </tr>
  </table>	
</div> <!-- //archivar -->
