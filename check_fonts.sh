#!/bin/bash
# Скрипт для проверки наличия файлов шрифтов

echo "=========================================="
echo "Проверка файлов шрифтов для иконок"
echo "=========================================="
echo ""

# Проверка Ionicons
echo "📦 IONICONS:"
echo "---"
if [ -f "public/ionicons/ionicons.min.css" ]; then
    echo "✅ ionicons.min.css - найден"
else
    echo "❌ ionicons.min.css - НЕ НАЙДЕН"
fi

if [ -f "public/ionicons/ionicons.eot" ]; then
    echo "✅ ionicons.eot - найден"
else
    echo "❌ ionicons.eot - НЕ НАЙДЕН"
fi

if [ -f "public/ionicons/ionicons.ttf" ]; then
    echo "✅ ionicons.ttf - найден"
else
    echo "❌ ionicons.ttf - НЕ НАЙДЕН"
fi

if [ -f "public/ionicons/ionicons.woff" ]; then
    echo "✅ ionicons.woff - найден"
else
    echo "❌ ionicons.woff - НЕ НАЙДЕН"
fi

if [ -f "public/ionicons/ionicons.svg" ]; then
    echo "✅ ionicons.svg - найден"
else
    echo "❌ ionicons.svg - НЕ НАЙДЕН"
fi

echo ""
echo "📦 FONT AWESOME:"
echo "---"
if [ -f "public/fa/fa.min.css" ]; then
    echo "✅ fa.min.css - найден"
else
    echo "❌ fa.min.css - НЕ НАЙДЕН"
fi

if [ -f "public/fa/fa-webfont.eot" ]; then
    echo "✅ fa-webfont.eot - найден"
else
    echo "❌ fa-webfont.eot - НЕ НАЙДЕН"
fi

if [ -f "public/fa/fa-webfont.woff2" ]; then
    echo "✅ fa-webfont.woff2 - найден"
else
    echo "❌ fa-webfont.woff2 - НЕ НАЙДЕН"
fi

if [ -f "public/fa/fa-webfont.woff" ]; then
    echo "✅ fa-webfont.woff - найден"
else
    echo "❌ fa-webfont.woff - НЕ НАЙДЕН"
fi

if [ -f "public/fa/fa-webfont.ttf" ]; then
    echo "✅ fa-webfont.ttf - найден"
else
    echo "❌ fa-webfont.ttf - НЕ НАЙДЕН"
fi

if [ -f "public/fa/fa-webfont.svg" ]; then
    echo "✅ fa-webfont.svg - найден"
else
    echo "❌ fa-webfont.svg - НЕ НАЙДЕН"
fi

echo ""
echo "=========================================="
echo "Структура папок:"
echo "=========================================="
echo ""
echo "public/ionicons/:"
ls -lh public/ionicons/ 2>/dev/null || echo "Папка не найдена"
echo ""
echo "public/fa/:"
ls -lh public/fa/ 2>/dev/null || echo "Папка не найдена"
echo ""

