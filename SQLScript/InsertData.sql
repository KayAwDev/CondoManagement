USE [TempCondo]
GO
INSERT [dbo].[LevelCode] ([LevelCode], [LevelDesc]) VALUES (1, N'Admin')
GO
INSERT [dbo].[LevelCode] ([LevelCode], [LevelDesc]) VALUES (2, N'Manager')
GO
INSERT [dbo].[LevelCode] ([LevelCode], [LevelDesc]) VALUES (3, N'Security')
GO
INSERT [dbo].[WebProgram] ([ProgramName], [ParentProgramName], [MenuName], [MenuSequence], [Active]) VALUES (N'Employees', N'SecurityAccessControl', N'Employees', 1, 1)
GO
INSERT [dbo].[WebProgram] ([ProgramName], [ParentProgramName], [MenuName], [MenuSequence], [Active]) VALUES (N'SecurityAccessControl', NULL, N'Security Access Control', 1, 1)
GO
INSERT [dbo].[WebProgram] ([ProgramName], [ParentProgramName], [MenuName], [MenuSequence], [Active]) VALUES (N'Units', NULL, N'Units', 2, 1)
GO
INSERT [dbo].[WebProgram] ([ProgramName], [ParentProgramName], [MenuName], [MenuSequence], [Active]) VALUES (N'Visitor', NULL, N'Visitor', 3, 1)
GO
INSERT [dbo].[WebProgram] ([ProgramName], [ParentProgramName], [MenuName], [MenuSequence], [Active]) VALUES (N'VisitorLog', N'Visitor', N'Visitor Log', 1, 1)
GO
INSERT [dbo].[WebProgram] ([ProgramName], [ParentProgramName], [MenuName], [MenuSequence], [Active]) VALUES (N'VisitorRegistration', N'Visitor', N'Visitor Registration', 2, 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (1, N'Employees', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (1, N'SecurityAccessControl', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (1, N'Units', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (1, N'Visitor', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (1, N'VisitorLog', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (1, N'VisitorRegistration', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (2, N'Employees', 0)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (2, N'SecurityAccessControl', 0)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (2, N'Units', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (2, N'Visitor', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (2, N'VisitorLog', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (2, N'VisitorRegistration', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (3, N'Employees', 0)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (3, N'SecurityAccessControl', 0)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (3, N'Units', 0)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (3, N'Visitor', 1)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (3, N'VisitorLog', 0)
GO
INSERT [dbo].[WebSecurity] ([Emp_Level], [ProgramName], [Allow]) VALUES (3, N'VisitorRegistration', 1)
GO
