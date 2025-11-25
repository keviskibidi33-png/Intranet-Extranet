/**
 * Script de verificación del layout
 * Inspecciona el DOM y muestra información sobre el header, breadcrumbs y sus estilos
 */

(function() {
  console.log('=== VERIFICACIÓN DE LAYOUT ===\n');
  
  // Verificar Header
  const header = document.querySelector('.header');
  if (header) {
    console.log('📋 HEADER:');
    console.log('  - Elemento encontrado:', header);
    const headerStyles = window.getComputedStyle(header);
    console.log('  - Display:', headerStyles.display);
    console.log('  - Position:', headerStyles.position);
    console.log('  - Height:', headerStyles.height);
    console.log('  - Margin:', headerStyles.margin);
    console.log('  - Margin-top:', headerStyles.marginTop);
    console.log('  - Margin-bottom:', headerStyles.marginBottom);
    console.log('  - Padding:', headerStyles.padding);
    console.log('  - Background:', headerStyles.backgroundColor);
    console.log('  - Border-bottom:', headerStyles.borderBottom);
    console.log('  - Top:', headerStyles.top);
    console.log('  - Z-index:', headerStyles.zIndex);
    console.log('  - OffsetHeight:', header.offsetHeight);
    console.log('  - OffsetTop:', header.offsetTop);
    console.log('');
  } else {
    console.log('❌ HEADER: No encontrado');
  }
  
  // Verificar Breadcrumbs
  const breadcrumbs = document.querySelector('.breadcrumbs');
  if (breadcrumbs) {
    console.log('📋 BREADCRUMBS:');
    console.log('  - Elemento encontrado:', breadcrumbs);
    const breadcrumbsStyles = window.getComputedStyle(breadcrumbs);
    console.log('  - Display:', breadcrumbsStyles.display);
    console.log('  - Position:', breadcrumbsStyles.position);
    console.log('  - Margin:', breadcrumbsStyles.margin);
    console.log('  - Margin-top:', breadcrumbsStyles.marginTop);
    console.log('  - Margin-bottom:', breadcrumbsStyles.marginBottom);
    console.log('  - Padding:', breadcrumbsStyles.padding);
    console.log('  - Padding-top:', breadcrumbsStyles.paddingTop);
    console.log('  - Background:', breadcrumbsStyles.backgroundColor);
    console.log('  - Border:', breadcrumbsStyles.border);
    console.log('  - OffsetHeight:', breadcrumbs.offsetHeight);
    console.log('  - OffsetTop:', breadcrumbs.offsetTop);
    console.log('');
  } else {
    console.log('❌ BREADCRUMBS: No encontrado');
  }
  
  // Verificar Right Panel
  const rightPanel = document.querySelector('.right-panel, #right-panel');
  if (rightPanel) {
    console.log('📋 RIGHT PANEL:');
    console.log('  - Elemento encontrado:', rightPanel);
    const rightPanelStyles = window.getComputedStyle(rightPanel);
    console.log('  - Display:', rightPanelStyles.display);
    console.log('  - Position:', rightPanelStyles.position);
    console.log('  - Margin:', rightPanelStyles.margin);
    console.log('  - Margin-left:', rightPanelStyles.marginLeft);
    console.log('  - Padding:', rightPanelStyles.padding);
    console.log('  - Background:', rightPanelStyles.backgroundColor);
    console.log('');
  } else {
    console.log('❌ RIGHT PANEL: No encontrado');
  }
  
  // Verificar distancia entre header y breadcrumbs
  if (header && breadcrumbs) {
    const headerRect = header.getBoundingClientRect();
    const breadcrumbsRect = breadcrumbs.getBoundingClientRect();
    const distance = breadcrumbsRect.top - headerRect.bottom;
    console.log('📏 DISTANCIA ENTRE HEADER Y BREADCRUMBS:');
    console.log('  - Header bottom:', headerRect.bottom);
    console.log('  - Breadcrumbs top:', breadcrumbsRect.top);
    console.log('  - Distancia:', distance + 'px');
    console.log('');
    
    if (distance > 0) {
      console.log('⚠️  PROBLEMA DETECTADO: Hay ' + distance + 'px de espacio entre el header y breadcrumbs');
      
      // Verificar qué está causando el espacio
      console.log('\n🔍 INVESTIGANDO CAUSA DEL ESPACIO:');
      
      // Verificar si hay elementos entre header y breadcrumbs
      let currentElement = header.nextElementSibling;
      let elementCount = 0;
      while (currentElement && currentElement !== breadcrumbs) {
        elementCount++;
        const elemStyles = window.getComputedStyle(currentElement);
        console.log('  - Elemento entre header y breadcrumbs #' + elementCount + ':', currentElement.tagName, currentElement.className);
        console.log('    - Display:', elemStyles.display);
        console.log('    - Height:', elemStyles.height);
        console.log('    - Margin:', elemStyles.margin);
        console.log('    - Padding:', elemStyles.padding);
        currentElement = currentElement.nextElementSibling;
      }
      
      if (elementCount === 0) {
        console.log('  - No hay elementos entre header y breadcrumbs');
        console.log('  - El espacio probablemente es causado por:');
        console.log('    * Margin-bottom del header:', headerStyles.marginBottom);
        console.log('    * Margin-top del breadcrumbs:', breadcrumbsStyles.marginTop);
        console.log('    * Padding-top del breadcrumbs:', breadcrumbsStyles.paddingTop);
        console.log('    * Line-height del breadcrumbs:', breadcrumbsStyles.lineHeight);
      }
    } else {
      console.log('✅ OK: No hay espacio entre header y breadcrumbs');
    }
  }
  
  // Verificar estilos aplicados desde diferentes hojas de estilo
  console.log('\n📚 HOJAS DE ESTILO CARGADAS:');
  const stylesheets = Array.from(document.styleSheets);
  stylesheets.forEach((sheet, index) => {
    try {
      console.log('  ' + (index + 1) + '. ' + (sheet.href || sheet.ownerNode?.innerHTML?.substring(0, 50) || 'Inline'));
    } catch (e) {
      console.log('  ' + (index + 1) + '. (No se puede acceder)');
    }
  });
  
  // Verificar reglas CSS específicas
  console.log('\n🎨 REGLAS CSS APLICADAS:');
  if (header) {
    console.log('  HEADER:');
    const headerRules = [];
    stylesheets.forEach(sheet => {
      try {
        const rules = sheet.cssRules || sheet.rules;
        if (rules) {
          Array.from(rules).forEach(rule => {
            if (rule.selectorText && header.matches(rule.selectorText)) {
              headerRules.push(rule.selectorText + ' { ... }');
            }
          });
        }
      } catch (e) {}
    });
    console.log('    - Reglas encontradas:', headerRules.length);
    headerRules.slice(0, 5).forEach(rule => console.log('      *', rule));
  }
  
  if (breadcrumbs) {
    console.log('  BREADCRUMBS:');
    const breadcrumbsRules = [];
    stylesheets.forEach(sheet => {
      try {
        const rules = sheet.cssRules || sheet.rules;
        if (rules) {
          Array.from(rules).forEach(rule => {
            if (rule.selectorText && breadcrumbs.matches(rule.selectorText)) {
              breadcrumbsRules.push(rule.selectorText + ' { ... }');
            }
          });
        }
      } catch (e) {}
    });
    console.log('    - Reglas encontradas:', breadcrumbsRules.length);
    breadcrumbsRules.slice(0, 5).forEach(rule => console.log('      *', rule));
  }
  
  console.log('\n=== FIN DE VERIFICACIÓN ===');
})();

