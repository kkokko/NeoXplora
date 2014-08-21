<table class="trainer">
  <tr>
    <td width="150">Sentence</td>
    <td><?php echo $this->sentence['name']; ?></td>
    <td width="220">
      <input type="hidden" class="sentenceID" value="<?php echo htmlspecialchars($this->sentence['id'], ENT_QUOTES); ?>" />
    </td>
  </tr>
  <tr>
    <td>Representation</td>
    <td>
      <input type="text" style="width:100%" class="newRepValue" value="<?php echo htmlspecialchars($this->sentence['representation'], ENT_QUOTES); ?>" />
    </td>
    <td>
      <a href="javascript:void(0)" class="btnDone button">Done</a> 
      <a href="javascript:void(0)" class="btnSkip button">Skip</a>
    </td>
  </tr>
  <tr>
    <td>Neo's Guess</td>
    <td class="repguess"><?php echo $this->sentence['guess']; ?></td>
    <td>
      <a href="javascript:void(0)" class="btnUse button">Correct</a> 
      <a href="javascript:void(0)" class="btnEdit button">Edit</a>
    </td>
  </tr>
</table>