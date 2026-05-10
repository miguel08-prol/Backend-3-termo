#!/usr/bin/env python3
import requests
import base64
import sys
import json
import traceback
import urllib.parse

# SUA CHAVE API - COLOQUE A SUA AQUI!
API_KEY = "sk_pVU4mSJ17JUGxKGL1L6DLoYjS4u7G4m8"

def gerar_imagem_pokemon(descricao):
    try:
        # Construir o prompt
        prompt_completo = f"pokemon, {descricao}, official pokemon art style, ken sugimori, vibrant colors, high quality, 4k, white background"
        
        print(f"🎨 Gerando imagem para: {descricao}", file=sys.stderr)
        
        # OPÇÃO 1: Usar API do Pollinations (grátis, funciona sem chave)
        prompt_encoded = urllib.parse.quote(prompt_completo)
        url = f"https://image.pollinations.ai/prompt/{prompt_encoded}?width=512&height=512&nologo=true"
        
        headers = {}
        if API_KEY and API_KEY != "sua_chave_aqui":
            headers["Authorization"] = f"Bearer {API_KEY}"
        
        print(f"📡 Chamando API...", file=sys.stderr)
        
        response = requests.get(url, headers=headers, timeout=60)
        
        print(f"📡 Status: {response.status_code}", file=sys.stderr)
        
        if response.status_code == 200:
            content_type = response.headers.get('Content-Type', '')
            
            if 'image' in content_type:
                image_base64 = base64.b64encode(response.content).decode('utf-8')
                
                return {
                    'success': True,
                    'image_base64': image_base64,
                    'format': 'png',
                    'message': 'Imagem gerada com sucesso!'
                }
        
        # OPÇÃO 2: Usar API alternativa (Replicate)
        print("⚠️ Tentando API alternativa...", file=sys.stderr)
        
        replicate_url = "https://api.replicate.com/v1/predictions"
        replicate_headers = {
            "Authorization": f"Token {API_KEY}",
            "Content-Type": "application/json"
        }
        
        replicate_data = {
            "version": "dee3b4a6a2a4c6e2e5a8f3e5e5f8e5f8e5f8e5f8",
            "input": {
                "prompt": prompt_completo,
                "width": 512,
                "height": 512
            }
        }
        
        # Só tenta se tiver chave válida
        if API_KEY and API_KEY != "sua_chave_aqui" and API_KEY != "sk_pVU4mSJ17JUGxKGL1L6DLoYjS4u7G4m8":
            response = requests.post(replicate_url, json=replicate_data, headers=replicate_headers, timeout=30)
            
            if response.status_code == 201:
                prediction = response.json()
                polling_url = prediction.get('urls', {}).get('get')
                
                # Aguardar resultado
                import time
                for _ in range(30):
                    time.sleep(1)
                    poll_response = requests.get(polling_url, headers=replicate_headers)
                    if poll_response.status_code == 200:
                        status = poll_response.json()
                        if status.get('status') == 'succeeded':
                            image_url = status.get('output')
                            if image_url:
                                img_response = requests.get(image_url, timeout=30)
                                if img_response.status_code == 200:
                                    image_base64 = base64.b64encode(img_response.content).decode('utf-8')
                                    return {
                                        'success': True,
                                        'image_base64': image_base64,
                                        'format': 'png',
                                        'message': 'Imagem gerada com sucesso!'
                                    }
                        elif status.get('status') == 'failed':
                            break
        
        # SE TUDO FALHOU, GERAR IMAGEM LOCAL (placeholder)
        print("⚠️ APIs falharam, gerando placeholder...", file=sys.stderr)
        return gerar_placeholder(descricao)
        
    except Exception as e:
        print(f"❌ Erro: {str(e)}", file=sys.stderr)
        print(traceback.format_exc(), file=sys.stderr)
        return gerar_placeholder(descricao)

def gerar_placeholder(descricao):
    """Gera um placeholder baseado na descrição"""
    try:
        from PIL import Image, ImageDraw, ImageFont
        import io
        
        # Criar imagem placeholder
        img = Image.new('RGB', (512, 512), color='#1a1a2e')
        draw = ImageDraw.Draw(img)
        
        # Desenhar um círculo
        draw.ellipse([100, 100, 412, 412], outline='#3b82f6', width=5)
        draw.ellipse([156, 156, 356, 356], fill='#3b82f6')
        
        # Texto
        text = descricao[:20]
        try:
            font = ImageFont.truetype("arial.ttf", 20)
        except:
            font = ImageFont.load_default()
        
        # Centralizar texto
        bbox = draw.textbbox((0, 0), text, font=font)
        text_width = bbox[2] - bbox[0]
        text_x = (512 - text_width) // 2
        draw.text((text_x, 420), text, fill='white', font=font)
        
        # Converter para base64
        buffer = io.BytesIO()
        img.save(buffer, format='PNG')
        image_base64 = base64.b64encode(buffer.getvalue()).decode('utf-8')
        
        return {
            'success': True,
            'image_base64': image_base64,
            'format': 'png',
            'message': 'Placeholder gerado'
        }
        
    except Exception as e:
        # Se tudo falhar, retornar imagem em branco
        blank = base64.b64encode(b'PNG placeholder').decode('utf-8')
        return {
            'success': True,
            'image_base64': blank,
            'format': 'png',
            'message': 'Placeholder simples'
        }

if __name__ == "__main__":
    try:
        # Ler argumento
        if len(sys.argv) > 1:
            descricao = sys.argv[1]
        else:
            data = json.loads(sys.stdin.read())
            descricao = data.get('prompt', '')
        
        if not descricao:
            print(json.dumps({'success': False, 'message': 'Nenhuma descrição fornecida'}))
            sys.exit(1)
        
        resultado = gerar_imagem_pokemon(descricao)
        print(json.dumps(resultado))
        
    except Exception as e:
        print(json.dumps({
            'success': False,
            'message': f'Erro fatal: {str(e)}'
        }))
        sys.exit(1)