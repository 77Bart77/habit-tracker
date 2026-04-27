import React from "react";
import { StyleSheet, Text, TextProps } from "react-native";
import { theme } from "./theme";

type Props = TextProps & {
  variant?: "h1" | "h2" | "body" | "muted" | "small";
};

export function AppText({ variant = "body", style, ...props }: Props) {
  return <Text {...props} style={[styles.base, styles[variant], style]} />;
}

const styles = StyleSheet.create({
  base: { color: theme.colors.text },
  h1: { fontSize: theme.textSize.h1, fontWeight: "800" },
  h2: { fontSize: theme.textSize.h2, fontWeight: "800" },
  body: { fontSize: theme.textSize.body },
  muted: { color: theme.colors.textMuted },
  small: { fontSize: theme.textSize.small, color: theme.colors.textMuted },
});