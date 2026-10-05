const {test}=require('node:test');
const assert=require('node:assert/strict');
const api=require('../site/assets/js/contact.js');
const fixture={enabled:true,ownerConfirmed:true,whatsappNumber:'595981234567'};
test('unconfirmed, invalid and placeholder recipients cannot create a WhatsApp destination',()=>{
  for (const config of [{}, {...fixture,enabled:false}, {...fixture,ownerConfirmed:false}, {...fixture,whatsappNumber:''}, {...fixture,whatsappNumber:'595000000000'}, {...fixture,whatsappNumber:'595999999999'}, {...fixture,whatsappNumber:'+595981234567'}, {...fixture,whatsappNumber:'595981234567<script>'}]) {
    assert.equal(api.isAvailable(config),false); assert.equal(api.whatsapp(config,'hello'),'');
  }
});
test('confirmed recipient creates correctly encoded Spanish context and city',()=>{
  const message=api.message('implantes o prótesis','Luque','/implantes/');
  const url=new URL(api.whatsapp(fixture,message));
  assert.equal(url.hostname,'wa.me'); assert.equal(url.pathname,'/595981234567');
  assert.equal(url.searchParams.get('text'),message);
  assert.match(message,/prótesis.*Luque.*disponibilidad, profesional y ubicación.*\/implantes\//);
});
test('untrusted query values do not appear in the consultation text',()=>{
  assert.deepEqual(api.selection('<script>','https://evil.example'),{topic:'',city:''});
  const msg=api.message('<script>','secret patient data','?token=private');
  assert.doesNotMatch(msg,/script|secret|token|private/); assert.match(msg,/Página: https:\/\/dentista\.com\.py\/contacto\//);
});

test('owner-confirmed recipient and dynamic messages identify the site, topic and full page URL',()=>{
  const config={...fixture,whatsappNumber:'595992279599'};
  for (const topic of api.topics) {
    const message=api.message(topic,'Asunción','/contacto/');
    const url=new URL(api.whatsapp(config,message));
    assert.equal(url.pathname,'/595992279599');
    assert.equal(url.searchParams.get('text'),message);
    assert.ok(message.includes('vengo de Dentista.com.py'));
    assert.ok(message.includes(topic));
    assert.ok(message.includes('https://dentista.com.py/contacto/'));
  }
});
test('selector fallback preserves only allowed optional answers in the contact URL',()=>{
  const url=new URL(api.contactPath('estética dental','Asunción'),'https://dentista.com.py');
  assert.equal(url.pathname,'/contacto/'); assert.equal(url.hash,'#canal');
  assert.equal(url.searchParams.get('tema'),'estética dental'); assert.equal(url.searchParams.get('ciudad'),'Asunción');
  assert.equal(api.contactPath('',''),'/contacto/#canal');
});
test('each message retains its page context without cross-CTA selection bleed',()=>{
  const original=api.message('urgencia dental','','/urgencias/');
  api.message('implantes o prótesis','Luque','/');
  assert.equal(api.message('urgencia dental','','/urgencias/'),original);
  assert.doesNotMatch(original,/Luque|implantes/);
});
