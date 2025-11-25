/**
 * Script de verificación para detectar espacios blancos innecesarios
 * entre el header y el breadcrumbs
 * Espera a que el DOM esté completamente cargado
 */

(function() {
  function verificarEspacios() {
    console.log('=== VERIFICACIÓN DE ESPACIOS BLANCOS ===');
    
    // Verificar Header - múltiples selectores posibles
    let header = document.querySelector('.header') || 
                 document.querySelector('#header') || 
                 document.querySelector('header.header') ||
                 document.querySelector('header#header');
    
    if (header) {
      const headerStyles = window.getComputedStyle(header);
      console.log('--- HEADER ENCONTRADO ---');
      console.log('Selector:', header.className || header.id);
      console.log('Height:', headerStyles.height);
      console.log('Margin:', headerStyles.margin);
      console.log('Margin-top:', headerStyles.marginTop);
      console.log('Margin-bottom:', headerStyles.marginBottom);
      console.log('Padding:', headerStyles.padding);
      console.log('Padding-top:', headerStyles.paddingTop);
      console.log('Padding-bottom:', headerStyles.paddingBottom);
      console.log('Position:', headerStyles.position);
      console.log('Top:', headerStyles.top);
      console.log('Bottom:', headerStyles.bottom);
      console.log('Display:', headerStyles.display);
      console.log('Line-height:', headerStyles.lineHeight);
      console.log('Rect:', header.getBoundingClientRect());
      
      // Verificar elementos hijos
      console.log('Elementos hijos:', header.children.length);
      Array.from(header.children).forEach((child, index) => {
        const childStyles = window.getComputedStyle(child);
        console.log(`  Hijo ${index + 1}: ${child.tagName}.${child.className} - Margin: ${childStyles.margin}, Padding: ${childStyles.padding}`);
      });
    } else {
      console.log('❌ Header no encontrado con selectores: .header, #header, header.header, header#header');
      // Buscar cualquier header
      const allHeaders = document.querySelectorAll('header');
      console.log('Headers encontrados en el DOM:', allHeaders.length);
      allHeaders.forEach((h, i) => {
        console.log(`  Header ${i + 1}:`, h.className, h.id, h.tagName);
      });
    }
    
    // Verificar Breadcrumbs - múltiples selectores posibles
    let breadcrumbs = document.querySelector('.breadcrumbs') || 
                      document.querySelector('div.breadcrumbs');
    
    if (breadcrumbs) {
      const breadcrumbsStyles = window.getComputedStyle(breadcrumbs);
      console.log('\n--- BREADCRUMBS ENCONTRADO ---');
      console.log('Selector:', breadcrumbs.className);
      console.log('Margin:', breadcrumbsStyles.margin);
      console.log('Margin-top:', breadcrumbsStyles.marginTop);
      console.log('Margin-bottom:', breadcrumbsStyles.marginBottom);
      console.log('Padding:', breadcrumbsStyles.padding);
      console.log('Padding-top:', breadcrumbsStyles.paddingTop);
      console.log('Padding-bottom:', breadcrumbsStyles.paddingBottom);
      console.log('Position:', breadcrumbsStyles.position);
      console.log('Top:', breadcrumbsStyles.top);
      console.log('Display:', breadcrumbsStyles.display);
      console.log('Line-height:', breadcrumbsStyles.lineHeight);
      console.log('Rect:', breadcrumbs.getBoundingClientRect());
      
      // Verificar elementos hijos
      console.log('Elementos hijos:', breadcrumbs.children.length);
      Array.from(breadcrumbs.children).forEach((child, index) => {
        const childStyles = window.getComputedStyle(child);
        console.log(`  Hijo ${index + 1}: ${child.tagName}.${child.className} - Margin: ${childStyles.margin}, Padding: ${childStyles.padding}`);
      });
    } else {
      console.log('❌ Breadcrumbs no encontrado con selectores: .breadcrumbs, div.breadcrumbs');
      // Buscar cualquier div con clase breadcrumbs
      const allDivs = document.querySelectorAll('div');
      const breadcrumbsDivs = Array.from(allDivs).filter(d => d.className.includes('breadcrumb'));
      console.log('Divs con "breadcrumb" en className:', breadcrumbsDivs.length);
      breadcrumbsDivs.forEach((d, i) => {
        console.log(`  Div ${i + 1}:`, d.className);
      });
    }
    
    // Verificar Right Panel
    let rightPanel = document.querySelector('.right-panel') || 
                     document.querySelector('#right-panel') ||
                     document.querySelector('div.right-panel') ||
                     document.querySelector('div#right-panel');
    
    if (rightPanel) {
      const rightPanelStyles = window.getComputedStyle(rightPanel);
      console.log('\n--- RIGHT PANEL ENCONTRADO ---');
      console.log('Margin:', rightPanelStyles.margin);
      console.log('Padding:', rightPanelStyles.padding);
      console.log('Display:', rightPanelStyles.display);
      console.log('Flex-direction:', rightPanelStyles.flexDirection);
      console.log('Gap:', rightPanelStyles.gap);
      console.log('Height:', rightPanelStyles.height);
    } else {
      console.log('❌ Right Panel no encontrado');
    }
    
    // Verificar espacio entre header y breadcrumbs
    if (header && breadcrumbs) {
      const headerRect = header.getBoundingClientRect();
      const breadcrumbsRect = breadcrumbs.getBoundingClientRect();
      const spaceBetween = breadcrumbsRect.top - headerRect.bottom;
      console.log('\n--- ESPACIO ENTRE HEADER Y BREADCRUMBS ---');
      console.log('Header bottom:', headerRect.bottom);
      console.log('Breadcrumbs top:', breadcrumbsRect.top);
      console.log('Espacio detectado:', spaceBetween + 'px');
      
      if (spaceBetween > 0) {
        console.log('⚠️ PROBLEMA DETECTADO: Hay ' + spaceBetween + 'px de espacio entre header y breadcrumbs');
        
        // Buscar elementos entre header y breadcrumbs
        const allElements = document.querySelectorAll('*');
        let elementsBetween = [];
        allElements.forEach(el => {
          if (el === header || el === breadcrumbs || header.contains(el) || breadcrumbs.contains(el)) {
            return; // Saltar el header, breadcrumbs y sus hijos
          }
          const rect = el.getBoundingClientRect();
          if (rect.top >= headerRect.bottom && rect.bottom <= breadcrumbsRect.top && rect.height > 0) {
            const styles = window.getComputedStyle(el);
            if (styles.display !== 'none' && styles.visibility !== 'hidden') {
              elementsBetween.push({
                element: el,
                tag: el.tagName,
                class: el.className,
                id: el.id,
                height: rect.height,
                margin: styles.margin,
                padding: styles.padding,
                display: styles.display,
                backgroundColor: styles.backgroundColor
              });
            }
          }
        });
        
        if (elementsBetween.length > 0) {
          console.log('\n--- ELEMENTOS ENTRE HEADER Y BREADCRUMBS ---');
          elementsBetween.forEach((item, index) => {
            console.log(`${index + 1}. ${item.tag}`, item.class || item.id || '(sin clase/id)', 
              '- Height:', item.height, 'px',
              '- Margin:', item.margin,
              '- Padding:', item.padding,
              '- Background:', item.backgroundColor);
            console.log('   Elemento completo:', item.element);
          });
        } else {
          console.log('No se encontraron elementos visibles entre header y breadcrumbs');
          console.log('El espacio probablemente es causado por margin o padding del header o breadcrumbs');
        }
      } else {
        console.log('✅ No hay espacio entre header y breadcrumbs');
      }
    }
    
    // Verificar estilos de otros CSS que puedan estar interfiriendo
    console.log('\n--- VERIFICAR ESTILOS DE OTROS CSS ---');
    const styleSheets = Array.from(document.styleSheets);
    styleSheets.forEach((sheet, index) => {
      try {
        const rules = Array.from(sheet.cssRules || sheet.rules || []);
        const relevantRules = rules.filter(rule => {
          if (rule.selectorText) {
            return rule.selectorText.includes('.header') || 
                   rule.selectorText.includes('.breadcrumbs') ||
                   rule.selectorText.includes('#header') ||
                   rule.selectorText.includes('.right-panel');
          }
          return false;
        });
        
        if (relevantRules.length > 0) {
          console.log(`Hoja de estilos ${index + 1}:`, sheet.href || 'inline');
          relevantRules.forEach(rule => {
            console.log(`  Regla: ${rule.selectorText}`);
            console.log(`    Estilos:`, rule.style.cssText);
          });
        }
      } catch (e) {
        // Ignorar errores de CORS
      }
    });
    
    console.log('\n=== FIN DE VERIFICACIÓN ===');
  }
  
  // Esperar a que el DOM esté completamente cargado
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', verificarEspacios);
  } else {
    // DOM ya está cargado, pero esperar un poco más para asegurar que todo esté renderizado
    setTimeout(verificarEspacios, 100);
  }
  
  // También ejecutar después de un tiempo adicional por si hay scripts que modifican el DOM
  setTimeout(verificarEspacios, 1000);
})();
