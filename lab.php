<?php
require_once 'api/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory | Secure Worldz Academy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="api/includes/loading_resilience.js?v=20260822" defer></script>
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
        /* ===== CSS RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            border-color: rgba(229, 231, 235, 0.3);
            outline-color: rgba(156, 163, 175, 0.5);
            overscroll-behavior: auto;
        }

        body {
            font-family: 'Roboto Mono', monospace;
            background-color: #000000;
            color: #ffffff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /* ===== CUSTOM FONTS ===== */
        @font-face {
            font-family: "Rebels";
            src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        /* ===== CUSTOM PROPERTIES (CSS Variables) ===== */
        :root {
            --radius: 0.625rem;
            --background: #000000;
            --foreground: #ffffff;
            --card: #080808;
            --card-foreground: #ffffff;
            --popover: #080808;
            --popover-foreground: #ffffff;
            --primary: #ff2a2f;
            --primary-foreground: #ffffff;
            --secondary: #080808;
            --secondary-foreground: #ffffff;
            --muted: #080808;
            --muted-foreground: #a0a0a0;
            --accent: rgba(248, 250, 252, 0.05);
            --accent-foreground: #ffffff;
            --border: rgba(139, 12, 16, 0.1);
            --pop: rgba(255, 255, 255, 0.025);
            --input: rgba(139, 12, 16, 0.15);
            --ring: rgba(148, 163, 184, 0.5);
            
            --success: #ff2a2f;
            --destructive: #ff2a2f;
            --warning: #8b0c10;
            
            --chart-1: #ff2a2f;
            --chart-2: #ff2a2f;
            --chart-3: #8b0c10;
            --chart-4: #ff2a2f;
            --chart-5: #ff2a2f;
            
            --sidebar: #080808;
            --sidebar-foreground: #ffffff;
            --sidebar-primary: #ff2a2f;
            --sidebar-primary-foreground: #ffffff;
            --sidebar-accent: rgba(248, 250, 252, 0.05);
            --sidebar-accent-foreground: #ffffff;
            --sidebar-border: rgba(139, 12, 16, 0.1);
            --sidebar-ring: rgba(148, 163, 184, 0.5);
            
            --gap: 1.5rem;
            --sides: 1.5rem;
            --header-mobile: 3.8rem;
        }

        /* DESKTOP-ONLY LAYOUT - Handled by app-theme-overrides.css */

        

        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            transition: opacity 0.5s ease, visibility 0.5s;
        }

        #loader {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(139, 92, 246, 0.1);
            border-top-color: #ff2a2f;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        #loader-text {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.75rem;
            color: #ff2a2f;
            letter-spacing: 0.2em;
            animation: pulse-loader 1.5s infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes pulse-loader { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }

        body.loaded #loader-wrapper {
            opacity: 0;
            visibility: hidden;
        }

        /* Left Sidebar - Navigation */
        

        /* Main Content Area - Handled by app-theme-overrides.css */

        /* ===== TYPOGRAPHY ===== */
        .font-display {
            font-family: 'Rebels', 'Roboto Mono', monospace;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .font-mono {
            font-family: 'Roboto Mono', monospace;
        }

        /* ===== UTILITY CLASSES ===== */
        .hidden {
            display: none !important;
        }

        .block {
            display: block;
        }

        .flex {
            display: flex;
        }

        .grid {
            display: grid;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .sticky {
            position: sticky;
        }

        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .inset-x-0 {
            left: 0;
            right: 0;
        }

        .top-0 {
            top: 0;
        }

        .bottom-0 {
            bottom: 0;
        }

        .right-0 {
            right: 0;
        }

        .left-0 {
            left: 0;
        }

        .z-10 {
            z-index: 10;
        }

        .z-20 {
            z-index: 20;
        }

        .z-30 {
            z-index: 30;
        }

        .z-40 {
            z-index: 40;
        }

        .z-50 {
            z-index: 50;
        }

        .w-full {
            width: 100%;
        }

        .h-full {
            height: 100%;
        }

        .h-screen {
            height: 100vh;
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .size-full {
            width: 100%;
            height: 100%;
        }

        .aspect-square {
            aspect-ratio: 1 / 1;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        .overflow-y-auto {
            overflow-y: auto;
        }

        .overflow-clip {
            overflow: clip;
        }

        .object-contain {
            object-fit: contain;
        }

        .object-cover {
            object-fit: cover;
        }

        .rounded {
            border-radius: 0.375rem;
        }

        .rounded-lg {
            border-radius: var(--radius);
        }

        .rounded-md {
            border-radius: calc(var(--radius) - 2px);
        }

        .rounded-sm {
            border-radius: calc(var(--radius) - 4px);
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .border {
            border-width: 1px;
        }

        .border-2 {
            border-width: 2px;
        }

        .border-b {
            border-bottom-width: 1px;
        }

        .border-t {
            border-top-width: 1px;
        }

        .ring-2 {
            box-shadow: 0 0 0 2px var(--ring);
        }

        .ring-pop {
            box-shadow: 0 0 0 2px var(--pop);
        }

        .bg-background {
            background-color: var(--background);
        }

        .bg-foreground {
            background-color: var(--foreground);
        }

        .bg-primary {
            background-color: var(--primary);
        }

        .bg-secondary {
            background-color: var(--secondary);
        }

        .bg-muted {
            background-color: var(--muted);
        }

        .bg-accent {
            background-color: var(--accent);
        }

        .bg-card {
            background-color: var(--card);
        }

        .bg-success {
            background-color: var(--success);
        }

        .bg-warning {
            background-color: var(--warning);
        }

        .bg-destructive {
            background-color: var(--destructive);
        }

        .bg-sidebar {
            background-color: var(--sidebar);
        }

        .bg-sidebar-primary {
            background-color: var(--sidebar-primary);
        }

        .bg-sidebar-accent {
            background-color: var(--sidebar-accent);
        }

        .text-foreground {
            color: var(--foreground);
        }

        .text-primary {
            color: var(--primary);
        }

        .text-primary-foreground {
            color: var(--primary-foreground);
        }

        .text-secondary {
            color: var(--secondary);
        }

        .text-secondary-foreground {
            color: var(--secondary-foreground);
        }

        .text-muted {
            color: var(--muted);
        }

        .text-muted-foreground {
            color: var(--muted-foreground);
        }

        .text-success {
            color: var(--success);
        }

        .text-warning {
            color: var(--warning);
        }

        .text-destructive {
            color: var(--destructive);
        }

        .text-sidebar-foreground {
            color: var(--sidebar-foreground);
        }

        .text-sidebar-primary {
            color: var(--sidebar-primary);
        }

        .text-sidebar-primary-foreground {
            color: var(--sidebar-primary-foreground);
        }

        .text-sidebar-accent-foreground {
            color: var(--sidebar-accent-foreground);
        }

        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .text-base {
            font-size: 1rem;
            line-height: 1.5rem;
        }

        .text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }

        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }

        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }

        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }

        .text-4xl {
            font-size: 2.25rem;
            line-height: 2.5rem;
        }

        .text-5xl {
            font-size: 3rem;
            line-height: 1;
        }

        .font-normal {
            font-weight: 400;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .italic {
            font-style: italic;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .opacity-0 {
            opacity: 0;
        }

        .opacity-10 {
            opacity: 0.1;
        }

        .opacity-20 {
            opacity: 0.2;
        }

        .opacity-30 {
            opacity: 0.3;
        }

        .opacity-40 {
            opacity: 0.4;
        }

        .opacity-50 {
            opacity: 0.5;
        }

        .opacity-60 {
            opacity: 0.6;
        }

        .opacity-70 {
            opacity: 0.7;
        }

        .opacity-80 {
            opacity: 0.8;
        }

        .opacity-90 {
            opacity: 0.9;
        }

        .opacity-100 {
            opacity: 1;
        }

        .grayscale {
            filter: grayscale(100%);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .cursor-grab {
            cursor: grab;
        }

        .cursor-grabbing {
            cursor: grabbing;
        }

        .select-none {
            user-select: none;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .transition-colors {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        .transition-opacity {
            transition: opacity 0.3s ease;
        }

        .transition-transform {
            transition: transform 0.3s ease;
        }

        .duration-200 {
            transition-duration: 0.2s;
        }

        .duration-300 {
            transition-duration: 0.3s;
        }

        .duration-500 {
            transition-duration: 0.5s;
        }

        .ease-out {
            transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
        }

        .ease-in-out {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .group:hover .group-hover\:opacity-100 {
            opacity: 1 !important;
        }

        .group:hover .group-hover\:scale-105 {
            transform: scale(1.05);
        }

        .group:hover .group-hover\:brightness-110 {
            filter: brightness(1.1);
        }

        /* ===== LAYOUT GRID ===== */
        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-cols-12 {
            grid-template-columns: repeat(12, minmax(0, 1fr));
        }

        .col-span-1 {
            grid-column: span 1 / span 1;
        }

        .col-span-2 {
            grid-column: span 2 / span 2;
        }

        .col-span-3 {
            grid-column: span 3 / span 3;
        }

        .col-span-7 {
            grid-column: span 7 / span 7;
        }

        .gap-1 {
            gap: 0.25rem;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 0.75rem;
        }

        .gap-4 {
            gap: 1rem;
        }

        .gap-6 {
            gap: 1.5rem;
        }

        .gap-8 {
            gap: 2rem;
        }

        .gap-10 {
            gap: 2.5rem;
        }

        .gap-gap {
            gap: var(--gap);
        }

        .p-0 {
            padding: 0;
        }

        .p-1 {
            padding: 0.25rem;
        }

        .p-2 {
            padding: 0.5rem;
        }

        .p-3 {
            padding: 0.75rem;
        }

        .p-4 {
            padding: 1rem;
        }

        .p-6 {
            padding: 1.5rem;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .px-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .py-8 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .py-10 {
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }

        .py-sides {
            padding-top: var(--sides);
            padding-bottom: var(--sides);
        }

        .px-sides {
            padding-left: var(--sides);
            padding-right: var(--sides);
        }

        .pt-1 {
            padding-top: 0.25rem;
        }

        .pt-2 {
            padding-top: 0.5rem;
        }

        .pt-4 {
            padding-top: 1rem;
        }

        .pb-1 {
            padding-bottom: 0.25rem;
        }

        .pb-4 {
            padding-bottom: 1rem;
        }

        .pl-2 {
            padding-left: 0.5rem;
        }

        .pl-3 {
            padding-left: 0.75rem;
        }

        .pl-4 {
            padding-left: 1rem;
        }

        .pr-1 {
            padding-right: 0.25rem;
        }

        .pr-2 {
            padding-right: 0.5rem;
        }

        .pr-3 {
            padding-right: 0.75rem;
        }

        .pr-4 {
            padding-right: 1rem;
        }

        .mt-1 {
            margin-top: 0.25rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-auto {
            margin-top: auto;
        }

        .mb-1 {
            margin-bottom: 0.25rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .ml-auto {
            margin-left: auto;
        }

        .mr-1 {
            margin-right: 0.25rem;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .mr-3 {
            margin-right: 0.75rem;
        }

        .space-y-1 > * + * {
            margin-top: 0.25rem;
        }

        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }

        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }

        .space-y-4 > * + * {
            margin-top: 1rem;
        }

        .space-y-6 > * + * {
            margin-top: 1.5rem;
        }

        .flex-1 {
            flex: 1 1 0%;
        }

        .flex-col {
            flex-direction: column;
        }

        .flex-row {
            flex-direction: row;
        }

        .items-start {
            align-items: flex-start;
        }

        .items-center {
            align-items: center;
        }

        .items-baseline {
            align-items: baseline;
        }

        .items-stretch {
            align-items: stretch;
        }

        .justify-start {
            justify-content: flex-start;
        }

        .justify-center {
            justify-content: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .justify-end {
            justify-content: flex-end;
        }

        .min-w-0 {
            min-width: 0;
        }

        .max-w-xs {
            max-width: 20rem;
        }

        .max-w-sm {
            max-width: 24rem;
        }

        .max-w-md {
            max-width: 28rem;
        }

        .max-w-max {
            max-width: max-content;
        }

        .w-1\/4 {
            width: 25%;
        }

        .w-14 {
            width: 3.5rem;
        }

        .w-16 {
            width: 4rem;
        }

        .w-56 {
            width: 14rem;
        }

        .w-80 {
            width: 20rem;
        }

        .h-5 {
            height: 1.25rem;
        }

        .h-6 {
            height: 1.5rem;
        }

        .h-7 {
            height: 1.75rem;
        }

        .h-8 {
            height: 2rem;
        }

        .h-10 {
            height: 2.5rem;
        }

        .h-12 {
            height: 3rem;
        }

        .h-14 {
            height: 3.5rem;
        }

        .h-32 {
            height: 8rem;
        }

        .h-header-mobile {
            height: var(--header-mobile);
        }

        .size-3 {
            width: 0.75rem;
            height: 0.75rem;
        }

        .size-4 {
            width: 1rem;
            height: 1rem;
        }

        .size-5 {
            width: 1.25rem;
            height: 1.25rem;
        }

        .size-6 {
            width: 1.5rem;
            height: 1.5rem;
        }

        .size-7 {
            width: 1.75rem;
            height: 1.75rem;
        }

        .size-9 {
            width: 2.25rem;
            height: 2.25rem;
        }

        .size-10 {
            width: 2.5rem;
            height: 2.5rem;
        }

        .size-12 {
            width: 3rem;
            height: 3rem;
        }

        .size-14 {
            width: 3.5rem;
            height: 3.5rem;
        }

        .size-16 {
            width: 4rem;
            height: 4rem;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ===== CUSTOM COMPONENT STYLES ===== */
        .card {
            background-color: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid transparent;
        }

        .badge-default {
            background-color: var(--primary);
            color: var(--primary-foreground);
            border-color: var(--primary);
        }

        .badge-secondary {
            background-color: var(--secondary);
            color: var(--secondary-foreground);
            border-color: var(--border);
        }

        .badge-outline {
            background-color: transparent;
            color: currentColor;
            border-color: currentColor;
        }

        .badge-outline-success {
            background-color: transparent;
            color: var(--success);
            border-color: var(--success);
        }

        .badge-outline-warning {
            background-color: transparent;
            color: var(--warning);
            border-color: var(--warning);
        }

        .badge-destructive {
            background-color: var(--destructive);
            color: white;
            border-color: var(--destructive);
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: calc(var(--radius) - 2px);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid transparent;
            user-select: none;
            white-space: nowrap;
        }

        .button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .button-default {
            background-color: var(--primary);
            color: var(--primary-foreground);
        }

        .button-default:hover:not(:disabled) {
            background-color: color-mix(in srgb, var(--primary) 90%, black);
        }

        .button-secondary {
            background-color: var(--secondary);
            color: var(--secondary-foreground);
            border-color: var(--border);
        }

        .button-secondary:hover:not(:disabled) {
            background-color: color-mix(in srgb, var(--secondary) 90%, black);
        }

        .button-ghost {
            background-color: transparent;
            color: currentColor;
        }

        .button-ghost:hover:not(:disabled) {
            background-color: var(--accent);
        }

        .button-outline {
            background-color: transparent;
            color: currentColor;
            border-color: currentColor;
        }

        .button-outline:hover:not(:disabled) {
            background-color: var(--accent);
        }

        .button-sm {
            height: 2rem;
            padding: 0 0.75rem;
            font-size: 0.875rem;
        }

        .button-md {
            height: 2.5rem;
            padding: 0 1rem;
            font-size: 0.875rem;
        }

        .button-lg {
            height: 3rem;
            padding: 0 1.5rem;
            font-size: 1rem;
        }

        .button-xl {
            height: 3.5rem;
            padding: 0 2rem;
            font-size: 1rem;
        }

        .button-icon {
            width: 2.5rem;
            height: 2.5rem;
            padding: 0;
        }

        .input {
            width: 100%;
            height: 2.5rem;
            padding: 0 0.75rem;
            background-color: var(--input);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) - 2px);
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }

        .input:focus {
            outline: none;
            border-color: var(--ring);
        }

        .input::placeholder {
            color: var(--muted-foreground);
            opacity: 0.7;
        }

        .separator {
            width: 100%;
            height: 1px;
            background-color: var(--border);
        }

        .bullet {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background-color: var(--muted-foreground);
        }

        .bullet-success {
            background-color: var(--success);
        }

        .bullet-warning {
            background-color: var(--warning);
        }

        .bullet-destructive {
            background-color: var(--destructive);
        }

        .bullet-sm {
            width: 0.375rem;
            height: 0.375rem;
        }

        .sheet {
            position: fixed;
            z-index: 50;
            background-color: var(--background);
            transition: transform 0.3s ease;
        }

        .sheet-bottom {
            bottom: 0;
            left: 0;
            right: 0;
            transform: translateY(100%);
        }

        .sheet-bottom.open {
            transform: translateY(0);
        }

        .sheet-right {
            top: 0;
            right: 0;
            bottom: 0;
            transform: translateX(100%);
        }

        .sheet-right.open {
            transform: translateX(0);
        }

        .tooltip {
            position: relative;
        }

        .tooltip-content {
            position: absolute;
            z-index: 50;
            padding: 0.5rem 0.75rem;
            background-color: var(--popover);
            color: var(--popover-foreground);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) - 2px);
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .tooltip:hover .tooltip-content {
            opacity: 1;
        }

        .tabs-list {
            display: flex;
            gap: 0.5rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 0.5rem;
        }

        .tabs-trigger {
            padding: 0.5rem 1rem;
            background: transparent;
            border: none;
            color: var(--muted-foreground);
            font-family: inherit;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: color 0.2s;
            position: relative;
        }

        .tabs-trigger:hover {
            color: var(--foreground);
        }

        .tabs-trigger.active {
            color: var(--foreground);
        }

        .tabs-trigger.active::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--primary);
        }

        /* ===== SIDEBAR NAVIGATION STYLES ===== */
        

        .nav-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
        }

        .nav-title .bullet {
            width: 0.375rem;
            height: 0.375rem;
        }

        .nav-title span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--muted-foreground);
        }

        

        

        

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slideUp {
            animation: slideUp 0.3s ease-out;
        }

        /* ===== LAB CARD STYLES ===== */
        .lab-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .desktop-main > .card:first-child {
            flex-shrink: 0;
        }

        .lab-card {
            background-color: var(--bg-card, rgba(16, 21, 31, 0.85));
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: var(--radius-lg, 14px);
            border: 1px solid var(--border-subtle, rgba(255, 255, 255, 0.08));
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .lab-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #ff2a2f 0%, #c0151a 50%, #ff2a2f 100%);
            z-index: 2;
        }

        .lab-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px -8px rgba(0, 0, 0, 0.8), 0 0 24px rgba(255, 42, 47, 0.25);
            border-color: rgba(255, 42, 47, 0.45);
        }

        .lab-img {
            height: auto;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #0f0f14 0%, #07090e 100%);
        }

        .lab-img::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 65%, rgba(7, 9, 14, 0.3) 100%);
        }

        .lab-img img {
            display: block;
            width: 100%;
            height: auto;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            filter: brightness(0.85) contrast(1.05);
        }

        .lab-card:hover .lab-img img {
            filter: brightness(1) contrast(1);
        }

        .lab-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        .lab-content h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #ffffff;
            letter-spacing: -0.01em;
        }

        .lab-content p {
            color: var(--muted-fg, #94a3b8);
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex-grow: 1;
            font-size: 0.875rem;
        }

        .lab-btn {
            background: linear-gradient(135deg, #ff2a2f 0%, #b81419 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md, 10px);
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.85rem;
            width: 100%;
            position: relative;
            box-shadow: 0 4px 14px rgba(255, 42, 47, 0.35);
        }

        .lab-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 42, 47, 0.55);
            filter: brightness(1.08);
        }

        .lab-btn::after {
            content: '→';
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
            position: absolute;
            right: 1.5rem;
        }

        .lab-btn:hover::after {
            opacity: 1;
            transform: translateX(0);
        }

        /* ===== STATUS INDICATOR ===== */
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--success);
        }

        .status-dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background-color: var(--success);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--muted);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--muted-foreground);
        }

        
        

        
        

        

        

        

        

        
        

        

        

        

        

        

        

        

        .ripple-container {
            position: relative;
            overflow: hidden;
        }

</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div id="loader-text">INITIALIZING ECOSYSTEM...</div>
    </div>

    
        <script>
            window.addEventListener('load', () => {
                setTimeout(() => {
                    document.body.classList.add('loaded');
                }, 500);
            });
        </script>
<div class="desktop-container">
        <!-- Left Sidebar - Navigation -->
        <?php include 'sidebar.php'; ?>

    <div class="desktop-main">
            <!-- Dashboard Header -->
            <div class="card">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded bg-primary flex items-center justify-center">
                            <svg class="size-5 text-primary-foreground" viewBox="0 0 20 20" fill="none">
                                <path stroke="currentColor" stroke-width="1.667" d="M16.228 3.772c1.31 1.31-.416 5.16-3.856 8.6-3.44 3.44-7.29 5.167-8.6 3.856-1.31-1.31.415-5.16 3.855-8.6 3.44-3.44 7.29-5.167 8.6-3.856Z"/>
                                <path stroke="currentColor" stroke-width="1.667" d="M16.228 16.228c-1.31 1.31-5.161-.416-8.601-3.855-3.44-3.44-5.166-7.29-3.856-8.601 1.31-1.31 5.162.416 8.601 3.855 3.44 3.44 5.166 7.29 3.856 8.601Z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-display">Laboratory</h1>
                            <div class="text-sm text-muted-foreground">Access interactive coding environments and Linux terminal</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lab Grid -->
            <section class="lab-grid fade-in">

                <div class="lab-card animate-fadeIn">
                    <div class="lab-img">
                        <img src="images/eaglone/ep.png" alt="Compilers" loading="lazy"> 
                    </div>
                    <div class="lab-content">
                        <h3>Compilers and Interpreters</h3>
                        <p>Unleash your programming skills with our inbuilt compilers and interpreters. Code, compile, and test in multiple languages in a seamless environment designed for learning and experimentation.</p>
                        <button class="lab-btn" onclick="location.href='lab/langs.php'">
                            Go to lab
                        </button>
                    </div>
                </div>

                <div class="lab-card animate-fadeIn" style="animation-delay: 0.1s">
                    <div class="lab-img">
                        <img src="images/eaglone/es.png" alt="linux terminal" loading="lazy"> 
                    </div>
                    <div class="lab-content">
                        <h3>Linux Terminal</h3>
                        <p>Master Linux commands with our inbuilt terminal. Practice shell scripting, system administration, and learn essential Linux skills in a safe, interactive environment.</p>
                        <button class="lab-btn" onclick="location.href='lab/lin_term.php'">
                            Go to lab
                        </button>
                    </div>
                </div>
            </section>
    </div>
</div>

    <script>
// Heartbeat & Online Status
setInterval(() => {
    fetch('api/heartbeat.php').then(r => r.json()).catch(e => console.error("Heartbeat error:", e));
}, 60000);

        document.addEventListener('DOMContentLoaded', function() {
            // Animate elements on load
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!entry.target.classList.contains('animate-fadeIn') && 
                            !entry.target.classList.contains('animate-slideUp')) {
                            entry.target.classList.add('animate-fadeIn');
                        }
                    }
                });
            }, observerOptions);

            // Observe all cards for animation
            const cards = document.querySelectorAll('.card, .lab-card');
            cards.forEach(card => observer.observe(card));

            // Update lab usage stats (simulated)
            setInterval(() => {
                const stats = document.querySelectorAll('.h-full.bg-success, .h-full.bg-primary');
                stats.forEach(stat => {
                    const currentWidth = parseInt(stat.style.width);
                    if (currentWidth < 95) {
                        const newWidth = Math.min(95, currentWidth + Math.random() * 2);
                        stat.style.width = `${newWidth}%`;
                        
                        // Update percentage text
                        const parent = stat.closest('div').previousElementSibling;
                        if (parent && parent.querySelector('.font-medium')) {
                            parent.querySelector('.font-medium').textContent = `${Math.round(newWidth)}%`;
                        }
                    }
                });
            }, 30000); // Update every 30 seconds
        });
    </script>
</body>
</html>
