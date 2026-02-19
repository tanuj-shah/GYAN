# Security Policy

## Supported Versions

| Version | Supported |
|---------|-----------|
| Latest (main branch) | ✅ |

## Reporting a Vulnerability

We take security seriously. If you discover a vulnerability, please report it responsibly.

### How to Report

1. **Email**: Send details to **gyan@ird.com.np** with subject line `[SECURITY] <brief description>`
2. **Do NOT** open a public GitHub issue for security vulnerabilities

### What to Include

- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if any)

### Response Timeline

| Action | Timeframe |
|--------|-----------|
| Acknowledgement | Within 48 hours |
| Initial assessment | Within 5 business days |
| Fix release (critical) | Within 14 days |
| Fix release (non-critical) | Next scheduled release |

### Scope

The following are in scope:
- Authentication and session management
- SQL injection, XSS, CSRF vulnerabilities
- File upload security bypasses
- Access control issues (privilege escalation)
- Sensitive data exposure

The following are **out of scope**:
- Denial of Service (DoS) attacks
- Social engineering
- Issues in third-party dependencies (report upstream)
- Issues requiring physical access

### Recognition

We appreciate responsible disclosure. Contributors who report valid vulnerabilities will be credited in our release notes (unless they prefer to remain anonymous).

## Security Best Practices for Contributors

- Never commit credentials, API keys, or secrets
- Use `.env` for all sensitive configuration (see `.env.example`)
- Use parameterized queries (PDO prepared statements) for all database operations
- Sanitize and validate all user input
- Use `htmlspecialchars()` for all output rendering
